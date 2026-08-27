<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\ClassSection;
use App\Models\Semester;
use App\Models\User;
use App\Models\CourseEnrollment;
use App\Models\FacultyCourse;
use App\Models\StaffCourse;
use App\Notifications\FacultyAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['department', 'program'])->orderBy('name');
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $courses = $query->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->get();
        return view('admin.courses.index', compact('courses', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.courses.create', compact('departments', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|max:20|unique:courses,code',
            'department_id'   => 'required|exists:departments,id',
            'program_id'      => 'required|exists:programs,id',
            'credits'         => 'required|integer|min:1|max:10',
            'semester_number' => 'required|integer|min:1|max:12',
            'description'     => 'nullable|string',
        ]);
        Course::create($request->only(
            'name', 'code', 'department_id', 'program_id',
            'credits', 'semester_number', 'description'
        ) + ['is_active' => true]);
        return redirect()->route('admin.courses.index')->with('success', 'Course created.');
    }

    public function edit(Course $course)
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.courses.edit', compact('course', 'departments', 'programs'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => ['required', 'string', 'max:20', Rule::unique('courses')->ignore($course->id)],
            'department_id'   => 'required|exists:departments,id',
            'program_id'      => 'required|exists:programs,id',
            'credits'         => 'required|integer|min:1|max:10',
            'semester_number' => 'required|integer|min:1|max:12',
            'description'     => 'nullable|string',
            'is_active'       => 'boolean',
        ]);
        $course->update($request->only(
            'name', 'code', 'department_id', 'program_id',
            'credits', 'semester_number', 'description'
        ) + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    // Sections
    public function sections(Course $course)
    {
        $semesters = Semester::orderBy('name')->get();
        $sections  = $course->classSections()->with(['semester', 'enrollments'])->paginate(15);
        return view('admin.courses.sections', compact('course', 'sections', 'semesters'));
    }

    public function storeSection(Request $request, Course $course)
    {
        $request->validate([
            'name'        => 'required|string|max:50',
            'semester_id' => 'required|exists:semesters,id',
            'max_students'=> 'required|integer|min:1|max:200',
        ]);
        $course->classSections()->create($request->only('name', 'semester_id', 'max_students') + ['is_active' => true]);
        return back()->with('success', 'Section created.');
    }

    // Enrollments
    public function enrollments()
    {
        $enrollments = CourseEnrollment::with(['student', 'section.course', 'semester'])
            ->latest()->paginate(20);
        $students  = User::where('role', 'student')->where('is_active', true)->orderBy('name')->get();
        $sections  = ClassSection::with('course')->where('is_active', true)->get();
        $semesters = Semester::orderBy('name')->get();
        return view('admin.enrollments.index', compact('enrollments', 'students', 'sections', 'semesters'));
    }

    public function storeEnrollment(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'semester_id'      => 'required|exists:semesters,id',
        ]);
        CourseEnrollment::firstOrCreate(
            [
                'user_id'          => $request->user_id,
                'class_section_id' => $request->class_section_id,
                'semester_id'      => $request->semester_id,
            ],
            ['status' => 'active', 'enrolled_at' => now()]
        );
        return back()->with('success', 'Student enrolled.');
    }

    // Faculty assignments
    public function assignFaculty(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'semester_id'      => 'required|exists:semesters,id',
        ]);
        $faculty = User::findOrFail($request->user_id);
        if (!$faculty->isFaculty()) {
            return back()->with('error', 'Selected user is not faculty.');
        }
        $assignment = FacultyCourse::firstOrCreate(
            [
                'user_id'          => $request->user_id,
                'class_section_id' => $request->class_section_id,
                'semester_id'      => $request->semester_id,
            ],
            ['is_active' => true]
        );

        if ($assignment->wasRecentlyCreated) {
            $faculty->notify(new FacultyAssignedNotification($assignment));
        }

        return back()->with('success', 'Faculty assigned.');
    }
}
