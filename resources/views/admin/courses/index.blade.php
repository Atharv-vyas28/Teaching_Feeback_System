@extends('layouts.dashboard')
@section('title', 'Manage Courses')
@php $header = 'Courses'; $subheader = 'Manage all courses across departments.'; @endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Courses</h3>
        <a href="{{ route('admin.courses.create') }}" class="btn-primary btn-sm">Add Course</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.courses.index') }}" style="margin-bottom:20px; display:flex; gap:10px; max-width:400px;">
            <select name="department_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </form>

        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Credits</th>
                        <th>Sem</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td style="font-weight:600; color:#0F172A;">{{ $course->code }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->department->code ?? 'N/A' }}</td>
                        <td>{{ $course->credits }}</td>
                        <td>{{ $course->semester_number ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $course->is_active ? 'badge-green' : 'badge-gray' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn-secondary btn-sm">Edit</a>
                                <a href="{{ route('admin.courses.sections', $course) }}" class="btn-primary btn-sm">Sections</a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm" data-confirm="Are you sure you want to delete this course?">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($courses->hasPages())
        <div style="margin-top:16px;">{{ $courses->links() }}</div>
        @endif
    </div>
</div>
@endsection
