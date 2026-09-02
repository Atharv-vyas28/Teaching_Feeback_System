@extends('layouts.dashboard')

@section('title', 'Student Directory')

@php
    $header = 'Student Directory';
    $subheader = 'Find students by branch, programme, course, and semester.';
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Students</h3>

        <a href="{{ route('admin.users.create') }}" class="btn-primary btn-sm">
            Add Student
        </a>
    </div>

    <div class="card-body">
        <form
            method="GET"
            action="{{ route('admin.students.directory') }}"
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:22px;"
        >
            <div>
                <label class="form-label">Branch / Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>

                    @foreach($departments as $department)
                        <option
                            value="{{ $department->id }}"
                            @selected($filters['department_id'] == $department->id)
                        >
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Programme</label>
                <select name="program_id" class="form-select">
                    <option value="">All Programmes</option>

                    @foreach($programs as $program)
                        <option
                            value="{{ $program->id }}"
                            @selected($filters['program_id'] == $program->id)
                        >
                            {{ $program->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>

                    @foreach($courses as $course)
                        <option
                            value="{{ $course->id }}"
                            @selected($filters['course_id'] == $course->id)
                        >
                            {{ $course->code }} — {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-select">
                    <option value="">All Semesters</option>

                    @foreach($semesters as $semester)
                        <option
                            value="{{ $semester->id }}"
                            @selected($filters['semester_id'] == $semester->id)
                        >
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Search</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    value="{{ $filters['search'] }}"
                    placeholder="Name, email, or roll number"
                >
            </div>

            <div style="display:flex;align-items:end;gap:8px;">
                <button type="submit" class="btn-primary">
                    Filter Students
                </button>

                <a
                    href="{{ route('admin.students.directory') }}"
                    class="btn-secondary"
                >
                    Reset
                </a>
            </div>
        </form>

        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Student</th>
                        <th>Branch</th>
                        <th>Programme</th>
                        <th>Current Semester</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <strong>
                                    {{ $student->roll_number ?? 'Not assigned' }}
                                </strong>
                            </td>

                            <td>
                                <div style="font-weight:700;color:#0F172A;">
                                    {{ $student->name }}
                                </div>

                                <div style="font-size:.76rem;color:#64748B;">
                                    {{ $student->email }}
                                </div>
                            </td>

                            <td>
                                {{ $student->department?->name ?? 'Not assigned' }}
                            </td>

                            <td>
                                {{ $student->program?->name ?? 'Not assigned' }}
                            </td>

                            <td>
                                Semester {{ $student->current_semester ?? '—' }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('admin.students.details', $student) }}"
                                    class="btn-secondary btn-sm"
                                >
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:38px;color:#94A3B8;">
                                No students match the selected branch, programme,
                                course, or semester.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div style="margin-top:18px;">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection