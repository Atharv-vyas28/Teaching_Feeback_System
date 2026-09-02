@extends('layouts.dashboard')
@section('title', 'Attendance Report')
@php $header = 'Attendance Report'; $subheader = 'Full attendance records across all sessions and students.'; @endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.attendance') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label class="form-label">Filter by Semester</label>
                <select name="semester_id" class="form-select" style="width:200px;">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ request('semester_id') == $sem->id ? 'selected' : '' }}>{{ $sem->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Filter by Student</label>
                <select name="student_id" class="form-select" style="width:220px;">
                    <option value="">All Students</option>
                    @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ request('student_id') == $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->roll_number }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Apply Filters</button>
            @if(request('semester_id') || request('student_id'))
            <a href="{{ route('admin.reports.attendance') }}" class="btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Attendance Records</h3>
        <span class="badge badge-blue">{{ $attendance->total() }} records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Session Date</th>
                    <th>Status</th>
                    <th>Feedback Enabled</th>
                    <th>Marked At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $rec)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $rec->student->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $rec->student->roll_number ?? '' }}</div>
                    </td>
                    <td>{{ $rec->session->section->course->name ?? 'N/A' }}</td>
                    <td>{{ $rec->session->session_date->format('M d, Y') ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ ['present'=>'badge-green','absent'=>'badge-red','late'=>'badge-yellow','excused'=>'badge-blue'][$rec->status] ?? 'badge-gray' }}">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $rec->feedback_enabled ? 'badge-green' : 'badge-gray' }}">
                            {{ $rec->feedback_enabled ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td style="color:#94A3B8;font-size:0.8rem;">{{ $rec->marked_at ? \Carbon\Carbon::parse($rec->marked_at)->format('M d, H:i') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-state">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendance->hasPages())
    <div style="padding:16px 20px;">{{ $attendance->links() }}</div>
    @endif
</div>
@endsection
