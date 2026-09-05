@extends('layouts.dashboard')
@section('title', 'Attendance Summary')
@php $header = 'Attendance Summary'; $subheader = 'Per-student attendance summary across your courses.'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('faculty.dashboard') }}" class="nav-link">Dashboard</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link">My Sessions</a>
<a href="{{ route('faculty.attendance.create') }}" class="nav-link">New Session</a>
<a href="{{ route('faculty.attendance.summary') }}" class="nav-link active">Attendance Summary</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('faculty.feedback.index') }}" class="nav-link">Feedback Sessions</a>
<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">My Ratings</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3>Filter Summary</h3>
    </div>
    <form method="GET" action="{{ route('faculty.attendance.summary') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">Course Section</label>
            <select name="section_id" class="form-input" style="min-width:200px;">
                <option value="">All My Sections</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec->id }}" {{ $filteredSectionId == $sec->id ? 'selected' : '' }}>
                        {{ $sec->course->name }} (Sec: {{ $sec->section_name }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">Search Student</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Roll No" class="form-input">
        </div>
        <div>
            <button type="submit" class="btn-primary" style="height:38px;">Apply Filters</button>
            @if(request()->hasAny(['section_id','date_from','date_to','search']))
                <a href="{{ route('faculty.attendance.summary') }}" class="btn-secondary" style="height:38px;display:inline-flex;align-items:center;">Clear</a>
            @endif
        </div>
        <div style="margin-left:auto;">
            <button type="submit" formaction="{{ route('faculty.attendance.export') }}" class="btn-secondary" style="height:38px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </button>
        </div>
    </form>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Course</th>
                    <th>Total Classes</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $row)
                @php
                    $pct = $row->total_classes > 0 ? round(($row->present_count / $row->total_classes) * 100, 1) : 0;
                    $color = $pct >= 75 ? '#15803D' : ($pct >= 60 ? '#B45309' : '#DC2626');
                @endphp
                <tr>
                    <td style="font-weight:600;color:#0F172A;">{{ $row->student_name }}</td>
                    <td>{{ $row->roll_number ?? '—' }}</td>
                    <td>
                        <div>{{ $row->course_name }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $row->course_code }}</div>
                    </td>
                    <td>{{ $row->total_classes }}</td>
                    <td style="color:#15803D;font-weight:600;">{{ $row->present_count }}</td>
                    <td style="color:#DC2626;font-weight:600;">{{ $row->absent_count }}</td>
                    <td><span class="badge" style="background:#F1F5F9;color:{{ $color }};font-weight:700;">{{ $pct }}%</span></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($summary->hasPages())
    <div style="padding:16px 20px;">{{ $summary->links() }}</div>
    @endif
</div>
@endsection
