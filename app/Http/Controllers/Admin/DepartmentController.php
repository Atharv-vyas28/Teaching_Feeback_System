<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['programs', 'users'])->orderBy('name')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string',
        ]);
        Department::create($request->only('name', 'code', 'description') + ['is_active' => true]);
        return back()->with('success', 'Department created.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $department->update($request->only('name', 'code', 'description') + ['is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }

    // Programs
    public function programs(Department $department)
    {
        $programs = $department->programs()->withCount('courses')->paginate(15);
        return view('admin.departments.programs', compact('department', 'programs'));
    }

    public function storeProgram(Request $request, Department $department)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:20|unique:programs,code',
            'duration_years' => 'required|integer|min:1|max:6',
            'description'    => 'nullable|string',
        ]);
        $department->programs()->create($request->only('name', 'code', 'duration_years', 'description') + ['is_active' => true]);
        return back()->with('success', 'Program created.');
    }
}
