<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('semesters')->orderByDesc('start_date')->get();
        return view('admin.semesters.index', compact('academicYears'));
    }

    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'is_current' => 'boolean',
        ]);
        if ($request->boolean('is_current')) {
            AcademicYear::query()->update(['is_current' => false]);
        }
        AcademicYear::create($request->only('name', 'start_date', 'end_date') + [
            'is_current' => $request->boolean('is_current'),
        ]);
        return back()->with('success', 'Academic year created.');
    }

    public function storeSemester(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|string|max:100',
            'number'           => 'required|integer|min:1|max:12',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'is_current'       => 'boolean',
        ]);
        if ($request->boolean('is_current')) {
            Semester::query()->update(['is_current' => false]);
        }
        Semester::create($request->only(
            'academic_year_id', 'name', 'number', 'start_date', 'end_date'
        ) + ['is_current' => $request->boolean('is_current')]);
        return back()->with('success', 'Semester created.');
    }

    public function setCurrentSemester(Semester $semester)
    {
        Semester::query()->update(['is_current' => false]);
        $semester->update(['is_current' => true]);
        return back()->with('success', 'Current semester updated.');
    }
}
