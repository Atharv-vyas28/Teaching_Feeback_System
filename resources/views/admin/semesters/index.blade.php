@extends('layouts.dashboard')
@section('title', 'Semesters')
@php $header = 'Semesters'; $subheader = 'Manage academic years and semesters.'; @endphp

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
<a href="{{ route('admin.semesters.index') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<a href="{{ route('admin.enrollments.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    Enrollments
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Semesters</h3>
    </div>
    <div class="card-body">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesters as $sem)
                    <tr>
                        <td style="font-weight:600; color:#0F172A;">{{ $sem->name }}</td>
                        <td>{{ $sem->academicYear->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($sem->start_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($sem->end_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge {{ $sem->is_current ? 'badge-green' : 'badge-gray' }}">
                                {{ $sem->is_current ? 'Current' : 'Past/Future' }}
                            </span>
                        </td>
                        <td>
                            @if(!$sem->is_current)
                            <form method="POST" action="{{ route('admin.semesters.set-current', $sem) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-primary btn-sm">Set Current</button>
                            </form>
                            @else
                            <span style="font-size:0.8rem;color:#94A3B8;">Active Semester</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">No semesters found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
