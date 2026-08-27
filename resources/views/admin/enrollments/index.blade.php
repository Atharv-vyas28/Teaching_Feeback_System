@extends('layouts.dashboard')
@section('title', 'Enrollments')
@php $header = 'Student Enrollments'; $subheader = 'Manage student enrollments and assign faculty to sections.'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Academic</div>
<a href="{{ route('admin.departments.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Departments
</a>
<a href="{{ route('admin.courses.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Courses
</a>
<a href="{{ route('admin.semesters.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<a href="{{ route('admin.enrollments.index') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    Enrollments
</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    {{-- Enroll Student --}}
    <div class="card">
        <div class="card-header"><h3>Enroll Student</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.enrollments.store') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label">Student</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select student...</option>
                        @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->roll_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->course->name }} - Sec {{ $s->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Enroll Student</button>
            </form>
        </div>
    </div>

    {{-- Assign Faculty --}}
    <div class="card">
        <div class="card-header"><h3>Assign Faculty</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.faculty.assign') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label">Faculty</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select faculty...</option>
                        @foreach(\App\Models\User::where('role','faculty')->get() as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->course->name }} - Sec {{ $s->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Assign Faculty</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Recent Enrollments</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course Section</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $e)
                <tr>
                    <td style="font-weight:600;color:#0F172A;">{{ $e->student->name ?? 'N/A' }} <span style="font-size:0.75rem;color:#94A3B8;">{{ $e->student->roll_number ?? '' }}</span></td>
                    <td>{{ $e->section->course->name ?? 'N/A' }} - {{ $e->section->section_name ?? 'N/A' }}</td>
                    <td>{{ $e->semester->name ?? 'N/A' }}</td>
                    <td><span class="badge {{ $e->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($e->status) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($e->enrolled_at)->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">No enrollments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div style="padding:16px;">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
