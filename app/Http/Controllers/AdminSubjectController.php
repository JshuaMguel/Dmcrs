<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $subjects = Subject::with('department')->orderBy('subject_code')->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $departments = Department::orderBy('name')->get();
        return view('admin.subjects.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'subject_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $subject->load('department');
        return view('admin.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $departments = Department::orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $subject->id,
            'subject_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    /**
     * Get subjects by department (for AJAX)
     */
    public function getByDepartment(Request $request)
    {
        $departmentId = $request->get('department_id');
        $subjects = Subject::where('department_id', $departmentId)
            ->orderBy('subject_code')
            ->get(['id', 'subject_code', 'subject_title']);

        return response()->json($subjects);
    }
}
