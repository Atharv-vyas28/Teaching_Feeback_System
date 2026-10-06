<?php

namespace App\Http\Controllers\HOD;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Http\Request;

class FeedbackSessionController extends Controller
{
    /**
     * HOD feedback-session home page.
     *
     * Shows courses from:
     * - current semester
     * - HOD's own department
     *
     * Uses the same Blade file as Admin.
     */
    public function index()
    {
        $hod = auth()->user();

        abort_unless(
            $hod->role === 'faculty' && $hod->is_head,
            403
        );

        $semester = Semester::where('is_current', true)->firstOrFail();

        $department = Department::findOrFail($hod->department_id);

        $sections = ClassSection::with('course')
            ->where('semester_id', $semester->id)
            ->where('is_active', true)
            ->whereHas('course', function ($query) use ($hod) {
                $query->where('department_id', $hod->department_id);
            })
            ->get();

        $courses = $sections
            ->groupBy('course_id')
            ->map(function ($courseSections) {

                $course = $courseSections->first()->course;

                return (object) [
                    'id' => $course->id,
                    'code' => $course->code,
                    'name' => $course->name,
                    'sections' => $courseSections,
                ];
            })
            ->values();

        return view('admin.feedback-sessions.index', [
            'level' => 'course',
            'semester' => $semester,
            'department' => $department,
            'courses' => $courses,
            'isHod' => true,
        ]);
    }
}