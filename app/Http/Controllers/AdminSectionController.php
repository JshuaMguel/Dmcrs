<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSectionController extends Controller
{
    private function checkAdminAccess()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin', 'super admin', 'superadmin'])) {
            abort(403, 'Unauthorized access. Your role: ' . (Auth::user()->role ?? 'none'));
        }
    }

    /**
     * Display a listing of sections.
     */
    public function index()
    {
        $this->checkAdminAccess();

        $sections = Section::with('department')->orderBy('department_id')->orderBy('year_level')->orderBy('section_name')->get();
        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        $this->checkAdminAccess();

        $departments = Department::orderBy('name')->get();
        return view('admin.sections.create', compact('departments'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validatedData = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_name' => 'required|string|max:10|regex:/^[A-Z]+$/',
        ]);

        // Check for duplicate section
        $exists = Section::where('department_id', $request->department_id)
            ->where('year_level', $request->year_level)
            ->where('section_name', $request->section_name)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['section_name' => 'This section already exists for the selected department and year level.'])
                ->withInput();
        }

        Section::create($validatedData);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully!');
    }

    /**
     * Display the specified section.
     */
    public function show(Section $section)
    {
        $this->checkAdminAccess();

        $section->load('department', 'makeupRequests');
        return view('admin.sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        $this->checkAdminAccess();

        $departments = Department::orderBy('name')->get();
        return view('admin.sections.edit', compact('section', 'departments'));
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Section $section)
    {
        $this->checkAdminAccess();

        $validatedData = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'year_level' => 'required|integer|min:1|max:6',
            'section_name' => 'required|string|max:10|regex:/^[A-Z]+$/',
        ]);

        // Check for duplicate section (excluding current section)
        $exists = Section::where('department_id', $request->department_id)
            ->where('year_level', $request->year_level)
            ->where('section_name', $request->section_name)
            ->where('id', '!=', $section->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['section_name' => 'This section already exists for the selected department and year level.'])
                ->withInput();
        }

        $section->update($validatedData);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully!');
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Section $section)
    {
        $this->checkAdminAccess();

        // Check if section has associated makeup requests
        if ($section->makeupRequests()->exists()) {
            return redirect()->route('admin.sections.index')
                ->with('error', 'Cannot delete section. It has associated makeup requests.');
        }

        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully!');
    }

    /**
     * Get sections by department (AJAX endpoint)
     */
    public function getByDepartment(Request $request)
    {
        $this->checkAdminAccess();

        $departmentId = $request->get('department_id');

        if (!$departmentId) {
            return response()->json([]);
        }

        $sections = Section::where('department_id', $departmentId)
            ->orderBy('year_level')
            ->orderBy('section_name')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'full_name' => $section->full_name
                ];
            });

        return response()->json($sections);
    }
}
