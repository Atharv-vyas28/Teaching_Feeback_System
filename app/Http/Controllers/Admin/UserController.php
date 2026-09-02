<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'program'])->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('roll_number', 'like', "%{$request->search}%")
                  ->orWhere('employee_id', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'departments', 'programs'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.users.create', compact('departments', 'programs'));
    }

    public function studentDirectory(Request $request)
{
    $filters = [
        'department_id' => $request->integer('department_id') ?: null,
        'program_id' => $request->integer('program_id') ?: null,
        'course_id' => $request->integer('course_id') ?: null,
        'semester_id' => $request->integer('semester_id') ?: null,
        'search' => trim((string) $request->input('search')),
    ];

    $studentsQuery = User::query()
        ->where('users.role', 'student')
        ->where('users.is_active', true)
        ->with(['department', 'program'])

        ->when($filters['department_id'], fn ($query) =>
            $query->where('users.department_id', $filters['department_id'])
        )

        ->when($filters['program_id'], fn ($query) =>
            $query->where('users.program_id', $filters['program_id'])
        )

        ->when($filters['search'], function ($query) use ($filters) {
            $search = '%' . $filters['search'] . '%';

            $query->where(function ($studentQuery) use ($search) {
                $studentQuery
                    ->where('users.name', 'like', $search)
                    ->orWhere('users.email', 'like', $search)
                    ->orWhere('users.roll_number', 'like', $search);
            });
        });

    /*
     * Course and Semester are determined from active course enrollment.
     * class_sections connects a course with a semester.
     */
    if ($filters['course_id'] || $filters['semester_id']) {
        $studentsQuery->whereIn('users.id', function ($query) use ($filters) {
            $query->select('course_enrollments.user_id')
                ->from('course_enrollments')
                ->join(
                    'class_sections',
                    'class_sections.id',
                    '=',
                    'course_enrollments.class_section_id'
                )
                ->where('course_enrollments.status', 'active')

                ->when($filters['course_id'], fn ($subQuery) =>
                    $subQuery->where(
                        'class_sections.course_id',
                        $filters['course_id']
                    )
                )

                ->when($filters['semester_id'], fn ($subQuery) =>
                    $subQuery->where(
                        'class_sections.semester_id',
                        $filters['semester_id']
                    )
                );
        });
    }

    $students = $studentsQuery
        ->orderBy('users.name')
        ->paginate(20)
        ->withQueryString();

    $departments = Department::where('is_active', true)
        ->orderBy('name')
        ->get();

    $programs = Program::where('is_active', true)
        ->orderBy('name')
        ->get();

    $courses = Course::where('is_active', true)
        ->orderBy('name')
        ->get();

    $semesters = Semester::orderBy('number')
        ->orderBy('name')
        ->get();

    return view(
        'admin.students.directory',
        compact(
            'students',
            'departments',
            'programs',
            'courses',
            'semesters',
            'filters'
        )
    );
}

public function studentDetails(User $student)
{
    abort_unless($student->role === 'student', 404);

    $student->load(['department', 'program']);

    $enrollments = DB::table('course_enrollments')
        ->join(
            'class_sections',
            'class_sections.id',
            '=',
            'course_enrollments.class_section_id'
        )
        ->join(
            'courses',
            'courses.id',
            '=',
            'class_sections.course_id'
        )
        ->leftJoin(
            'semesters',
            'semesters.id',
            '=',
            'class_sections.semester_id'
        )
        ->where('course_enrollments.user_id', $student->id)
        ->select(
            'courses.name as course_name',
            'courses.code as course_code',
            'class_sections.section_name',
            'semesters.name as semester_name',
            'course_enrollments.status as enrollment_status'
        )
        ->orderBy('semesters.number')
        ->orderBy('courses.name')
        ->get();

    return view(
        'admin.students.details',
        compact('student', 'enrollments')
    );
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:8|confirmed',
            'role'             => 'required|in:student,faculty,staff,admin',
            'department_id'    => 'nullable|exists:departments,id',
            'program_id'       => 'nullable|exists:programs,id',
            'roll_number'      => 'nullable|string|unique:users,roll_number',
            'employee_id'      => 'nullable|string|unique:users,employee_id',
            'current_semester' => 'nullable|integer|min:1|max:12',
            'phone'            => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'departments', 'programs'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'         => 'nullable|string|min:8|confirmed',
            'role'             => 'required|in:student,faculty,staff,admin',
            'department_id'    => 'nullable|exists:departments,id',
            'program_id'       => 'nullable|exists:programs,id',
            'roll_number'      => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'employee_id'      => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'current_semester' => 'nullable|integer|min:1|max:12',
            'phone'            => 'nullable|string|max:20',
            'is_active'        => 'boolean',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate yourself.');
        }
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }
}
