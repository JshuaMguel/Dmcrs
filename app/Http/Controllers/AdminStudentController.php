<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\Section;
use App\Models\MakeUpClassConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminStudentController extends Controller
{
    private function checkAdminAccess()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin', 'super admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = Student::with(['department', 'section'])->withoutGlobalScopes();

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->byDepartment($request->department_id);
        }


        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->paginate(15);
        $departments = Department::orderBy('name')->get();
        $studentsWithoutSections = Student::withoutGlobalScopes()->whereNull('section_id')->count();

        return view('admin.students.index', compact('students', 'departments', 'studentsWithoutSections'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $this->checkAdminAccess();

        $departments = Department::orderBy('name')->get();
        $sections = Section::with('department')->orderBy('department_id')->orderBy('year_level')->orderBy('section_name')->get();

        return view('admin.students.create', compact('departments', 'sections'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validatedData = $request->validate([
            'student_id_number' => 'required|string|max:50|unique:students,student_id_number',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email',
            'department_id' => 'required|exists:departments,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:active,inactive,graduated,dropped',
            'contact_number' => 'nullable|string|max:20',
        ]);

        // Validate section belongs to department
        if ($request->filled('section_id')) {
            $section = Section::findOrFail($request->section_id);
            if ($section->department_id != $request->department_id) {
                return back()->withErrors(['section_id' => 'Selected section does not belong to the selected department.'])->withInput();
            }
        }

        Student::create($validatedData);

        Log::info('Student created', ['student_id' => $validatedData['student_id_number'], 'created_by' => Auth::id()]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully!');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $this->checkAdminAccess();

        $departments = Department::orderBy('name')->get();
        $sections = Section::with('department')
            ->where('department_id', $student->department_id)
            ->orderBy('year_level')
            ->orderBy('section_name')
            ->get();

        return view('admin.students.edit', compact('student', 'departments', 'sections'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $this->checkAdminAccess();

        $validatedData = $request->validate([
            'student_id_number' => 'required|string|max:50|unique:students,student_id_number,' . $student->id,
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'department_id' => 'required|exists:departments,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:active,inactive,graduated,dropped',
            'contact_number' => 'nullable|string|max:20',
        ]);

        // Validate section belongs to department
        if ($request->filled('section_id')) {
            $section = Section::findOrFail($request->section_id);
            if ($section->department_id != $request->department_id) {
                return back()->withErrors(['section_id' => 'Selected section does not belong to the selected department.'])->withInput();
            }
        }

        $student->update($validatedData);

        Log::info('Student updated', ['student_id' => $student->id, 'updated_by' => Auth::id()]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from storage (soft delete).
     */
    public function destroy(Student $student)
    {
        $this->checkAdminAccess();

        // Check dependencies
        if (MakeUpClassConfirmation::where('student_email', $student->email)->exists()) {
            return back()->with('warning', 'Cannot delete. Student has confirmations. Set to Inactive instead.');
        }

        $student->delete(); // Soft delete

        Log::info('Student archived', ['student_id' => $student->id, 'archived_by' => Auth::id()]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student archived successfully!');
    }

    /**
     * Activate a student.
     */
    public function activate(Student $student)
    {
        $this->checkAdminAccess();

        $student->update(['status' => 'active']);

        return back()->with('success', 'Student activated successfully!');
    }

    /**
     * Deactivate a student.
     */
    public function deactivate(Student $student)
    {
        $this->checkAdminAccess();

        $student->update(['status' => 'inactive']);

        return back()->with('success', 'Student deactivated successfully!');
    }

    /**
     * Import students from CSV file.
     */
    public function import(Request $request)
    {
        $this->checkAdminAccess();

        // More flexible validation for CSV files
        $request->validate([
            'csv_file' => 'required|file|max:5120',
        ], [
            'csv_file.required' => 'Please select a CSV file to upload.',
            'csv_file.file' => 'The uploaded file is not valid.',
            'csv_file.max' => 'The file size must not exceed 5MB.',
        ]);

        // Check file extension manually
        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['csv', 'txt'])) {
            return back()->with('error', 'Invalid file type. Please upload a CSV or TXT file.')->withInput();
        }

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle); // Skip header row

            // Find column indices
            $columnMap = [];
            foreach ($header as $index => $columnName) {
                $columnName = strtolower(trim($columnName));
                $columnMap[$columnName] = $index;
            }
            
            // Debug: Log column mapping
            Log::info('CSV Column Mapping', ['columns' => $columnMap]);

            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                try {
                    // CRITICAL: Log that we're processing a row
                    Log::info('Processing CSV row', [
                        'row_number' => $rowNumber,
                        'row_data' => $row,
                        'column_map' => $columnMap
                    ]);
                    // Map CSV columns to database fields
                    $studentData = [
                        'student_id_number' => $this->getColumnValue($row, $columnMap, ['student_id', 'student_id_number', 'id']),
                        'first_name' => $this->getColumnValue($row, $columnMap, ['first_name', 'firstname']),
                        'middle_name' => $this->getColumnValue($row, $columnMap, ['middle_name', 'middlename', 'middle']),
                        'last_name' => $this->getColumnValue($row, $columnMap, ['last_name', 'lastname']),
                        'email' => $this->getColumnValue($row, $columnMap, ['email']),
                        'contact_number' => $this->getColumnValue($row, $columnMap, ['contact_number', 'contact', 'phone', 'mobile']),
                        'status' => strtolower($this->getColumnValue($row, $columnMap, ['status']) ?: 'active'),
                    ];

                    // Validate required fields
                    if (empty($studentData['student_id_number']) || empty($studentData['first_name']) || 
                        empty($studentData['last_name']) || empty($studentData['email'])) {
                        throw new \Exception('Missing required fields: student_id, first_name, last_name, or email');
                    }

                    // Find department by program name
                    $programName = $this->getColumnValue($row, $columnMap, ['program', 'department', 'dept']);
                    $department = null;
                    if ($programName) {
                        $department = Department::where('name', 'like', '%' . $programName . '%')->first();
                    }

                    if (!$department) {
                        throw new \Exception('Department not found for program: ' . $programName);
                    }

                    $studentData['department_id'] = $department->id;

                    // Get year level
                    $yearLevel = $this->getColumnValue($row, $columnMap, ['year_level', 'yearlevel', 'year', 'level']);
                    $studentData['year_level'] = $yearLevel ? (int)$yearLevel : 1;

                    // Section is REQUIRED - find or create section
                    $sectionName = $this->getColumnValue($row, $columnMap, ['section']);
                    
                    // CRITICAL DEBUG: Log everything about section detection
                    Log::info('=== SECTION DETECTION DEBUG ===', [
                        'student_id' => $studentData['student_id_number'] ?? 'unknown',
                        'section_value_from_csv' => $sectionName,
                        'section_value_is_empty' => empty($sectionName),
                        'column_map_all_keys' => array_keys($columnMap),
                        'column_map_has_section' => isset($columnMap['section']),
                        'row_data' => $row,
                        'column_map' => $columnMap
                    ]);
                    
                    if (empty($sectionName)) {
                        $errorMsg = 'Section is required. Please include section column in CSV (e.g., A, B, C, 1A, BSIT-1A). Found columns: ' . implode(', ', array_keys($columnMap));
                        Log::error('SECTION MISSING', [
                            'student_id' => $studentData['student_id_number'],
                            'available_columns' => array_keys($columnMap),
                            'error' => $errorMsg
                        ]);
                        throw new \Exception($errorMsg);
                    }
                    
                    $sectionName = trim($sectionName);
                    $sectionLetter = null;
                    
                    // Try to extract section letter from various formats
                    // Always use year_level from CSV, not from section name
                    // Formats: "BSIT-1A", "3A", "1A", "BSIT-3A", "A" (just letter)
                    if (preg_match('/^([A-Z]+)$/i', $sectionName, $matches)) {
                        // Format like "A", "B", "C" - just the letter
                        $sectionLetter = strtoupper(trim($matches[1]));
                    } elseif (preg_match('/(\d+)([A-Z]+)/i', $sectionName, $matches)) {
                        // Format like "3A" or "1A" - extract letter only (ignore year, use CSV year_level)
                        $sectionLetter = strtoupper(trim($matches[2]));
                    } elseif (preg_match('/[A-Z]+-(\d+)([A-Z]+)/i', $sectionName, $matches)) {
                        // Format like "BSIT-3A" - extract letter only (ignore year, use CSV year_level)
                        $sectionLetter = strtoupper(trim($matches[2]));
                    } elseif (preg_match('/-(\d+)([A-Z]+)$/i', $sectionName, $matches)) {
                        // Format like "BSIT-1A" with dash - extract letter only
                        $sectionLetter = strtoupper(trim($matches[2]));
                    } elseif (preg_match('/\s+(\d+)([A-Z]+)$/i', $sectionName, $matches)) {
                        // Format like "BSIT 1A" with space - extract letter only
                        $sectionLetter = strtoupper(trim($matches[2]));
                    } elseif (preg_match('/([A-Z]+)$/i', $sectionName, $matches)) {
                        // Last resort: extract any letters at the end
                        $sectionLetter = strtoupper(trim($matches[1]));
                    }
                    
                    if (!$sectionLetter || empty($sectionLetter)) {
                        throw new \Exception('Invalid section format: "' . $sectionName . '". Use format like: A, B, C, 1A, or BSIT-1A');
                    }
                    
                    // Find existing section
                    $section = Section::where('department_id', $department->id)
                                ->where('year_level', $studentData['year_level'])
                        ->where('section_name', $sectionLetter)
                        ->first();
                    
                    // Create section if it doesn't exist
                    if (!$section) {
                        $section = Section::create([
                            'department_id' => $department->id,
                            'year_level' => $studentData['year_level'], // Use year_level from CSV
                            'section_name' => $sectionLetter,
                        ]);
                        Log::info('Created new section during CSV import', [
                            'section_id' => $section->id,
                            'department_id' => $department->id,
                            'year_level' => $studentData['year_level'],
                            'section_name' => $sectionLetter
                        ]);
                    }
                    
                    $studentData['section_id'] = $section->id;
                    
                    Log::info('Assigned section to student', [
                        'student_id' => $studentData['student_id_number'],
                        'section_id' => $section->id,
                        'section_name' => $section->section_name,
                        'section_year' => $studentData['year_level'],
                        'department_id' => $department->id
                    ]);

                    // Validate status
                    if (!in_array($studentData['status'], ['active', 'inactive', 'graduated', 'dropped'])) {
                        $studentData['status'] = 'active';
                    }

                    // Check if student already exists
                    $existing = Student::where('student_id_number', $studentData['student_id_number'])
                        ->orWhere('email', $studentData['email'])
                        ->first();

                    // CRITICAL: Make sure section_id is in studentData before saving
                    if (!isset($studentData['section_id']) || empty($studentData['section_id'])) {
                        Log::error('SECTION_ID MISSING BEFORE SAVE', [
                            'student_id' => $studentData['student_id_number'],
                            'studentData_keys' => array_keys($studentData),
                            'section_id_value' => $studentData['section_id'] ?? 'NOT IN ARRAY'
                        ]);
                    }
                    
                    // Debug: Log studentData before save
                    Log::info('Student data before save', [
                        'student_id' => $studentData['student_id_number'],
                        'section_id_in_data' => $studentData['section_id'] ?? 'NOT SET',
                        'all_keys' => array_keys($studentData),
                        'full_studentData' => $studentData
                    ]);
                    
                    if ($existing) {
                        // Update existing student - FORCE section_id to be included
                        $existing->section_id = $studentData['section_id'] ?? null;
                        $existing->fill($studentData);
                        $existing->save();
                        $existing->refresh();
                        
                        Log::info('Updated existing student with section', [
                            'student_id' => $existing->student_id_number,
                            'section_id_in_db' => $existing->section_id ?? 'NULL',
                            'section_id_in_data' => $studentData['section_id'] ?? 'NOT SET',
                            'section_name' => $existing->section ? $existing->section->section_name : 'NO SECTION',
                            'section_loaded' => $existing->section ? 'YES' : 'NO'
                        ]);
                        $successCount++;
                    } else {
                        // Create new student - make sure section_id is included
                        $newStudent = Student::create($studentData);
                        $newStudent->refresh();
                        
                        Log::info('Created new student with section', [
                            'student_id' => $newStudent->student_id_number,
                            'section_id_in_db' => $newStudent->section_id ?? 'NULL',
                            'section_id_in_data' => $studentData['section_id'] ?? 'NOT SET',
                            'section_name' => $newStudent->section ? $newStudent->section->section_name : 'NO SECTION',
                            'section_loaded' => $newStudent->section ? 'YES' : 'NO'
                        ]);
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            fclose($handle);
            DB::commit();

            Log::info('Students imported', [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'imported_by' => Auth::id()
            ]);

            $message = "Import completed! {$successCount} student(s) imported successfully.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} error(s) occurred.";
            }

            return redirect()->route('admin.students.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student import failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Import failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper method to get column value from CSV row.
     */
    private function getColumnValue($row, $columnMap, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            // Try exact match first
            if (isset($columnMap[$name]) && isset($row[$columnMap[$name]])) {
                $value = trim($row[$columnMap[$name]]);
                if (!empty($value)) {
                    return $value;
                }
            }
            // Try case-insensitive match
            foreach ($columnMap as $colName => $index) {
                if (strtolower($colName) === strtolower($name) && isset($row[$index])) {
                    $value = trim($row[$index]);
                    if (!empty($value)) {
                        return $value;
                    }
                }
            }
        }
        return null;
    }
    
    /**
     * Auto-assign students to sections based on department and year level.
     * Creates sections if they don't exist (default: Section A for each dept/year combo).
     */
    public function autoAssignSections(Request $request)
    {
        $this->checkAdminAccess();
        
        $assigned = 0;
        $created = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            // Get all students without sections (including soft-deleted check)
            $students = Student::withoutGlobalScopes()->whereNull('section_id')->get();
            
            Log::info('Auto-assign sections started', ['students_count' => $students->count()]);
            
            foreach ($students as $student) {
                if (!$student->department_id || !$student->year_level) {
                    Log::warning('Student missing department or year level', [
                        'student_id' => $student->student_id_number,
                        'department_id' => $student->department_id,
                        'year_level' => $student->year_level
                    ]);
                    continue;
                }
                
                // Try to find existing section for this department/year (prefer Section A)
                $section = Section::where('department_id', $student->department_id)
                    ->where('year_level', $student->year_level)
                    ->orderBy('section_name')
                    ->first();
                
                // If no section exists, create one (default: Section A)
                if (!$section) {
                    $section = Section::create([
                        'department_id' => $student->department_id,
                        'year_level' => $student->year_level,
                        'section_name' => 'A'
                    ]);
                    $created++;
                    Log::info('Created new section', [
                        'section_id' => $section->id,
                        'department_id' => $student->department_id,
                        'year_level' => $student->year_level,
                        'section_name' => 'A'
                    ]);
                }
                
                // Assign student to section
                $oldSectionId = $student->section_id;
                $student->section_id = $section->id;
                $student->save();
                $student->refresh();
                
                if ($student->section_id == $section->id) {
                    $assigned++;
                    Log::info('Assigned section to student', [
                        'student_id' => $student->student_id_number,
                        'section_id' => $section->id,
                        'old_section_id' => $oldSectionId
                    ]);
                } else {
                    $errors[] = "Failed to assign section to {$student->student_id_number}";
                    Log::error('Failed to assign section', [
                        'student_id' => $student->student_id_number,
                        'expected_section_id' => $section->id,
                        'actual_section_id' => $student->section_id
                    ]);
                }
            }
            
            DB::commit();
            
            $message = "Successfully assigned {$assigned} student(s) to sections.";
            if ($created > 0) {
                $message .= " Created {$created} new section(s).";
            }
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " error(s) occurred.";
            }
            
            return redirect()->route('admin.students.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Auto-assign sections failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to assign sections: ' . $e->getMessage());
        }
    }
    
    /**
     * Quick fix: Directly assign sections to existing students based on CSV data
     */
    public function fixSectionsNow()
    {
        $this->checkAdminAccess();
        
        $assignments = [
            '2025-001' => ['dept_id' => 1, 'year' => 1, 'section' => 'A'],
            '2025-002' => ['dept_id' => 1, 'year' => 1, 'section' => 'B'],
            '2025-003' => ['dept_id' => 3, 'year' => 1, 'section' => 'B'],
            '2025-004' => ['dept_id' => 3, 'year' => 1, 'section' => 'A'],
            '2025-005' => ['dept_id' => 2, 'year' => 1, 'section' => 'C'],
        ];
        
        DB::beginTransaction();
        
        try {
            $assigned = 0;
            $created = 0;
            
            foreach ($assignments as $studentId => $info) {
                // Get or create section
                $section = Section::where('department_id', $info['dept_id'])
                    ->where('year_level', $info['year'])
                    ->where('section_name', $info['section'])
                    ->first();
                
                if (!$section) {
                    $section = Section::create([
                        'department_id' => $info['dept_id'],
                        'year_level' => $info['year'],
                        'section_name' => $info['section'],
                    ]);
                    $created++;
                }
                
                // Directly update student
                $updated = DB::table('students')
                    ->where('student_id_number', $studentId)
                    ->update(['section_id' => $section->id]);
                
                if ($updated) {
                    $assigned++;
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                ->with('success', "Fixed! Assigned {$assigned} student(s) to sections. Created {$created} new section(s).");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}


