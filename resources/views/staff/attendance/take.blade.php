@extends('layouts.dashboard')
@section('title', 'Take Attendance')
@php $header = 'Take Attendance'; $subheader = $classSession->section->course->name ?? 'Class Session'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<a href="{{ route('staff.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Course</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->section->course->name ?? 'N/A' }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Date</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->session_date->format('M d, Y') }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Time</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->start_time }} – {{ $classSession->end_time }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Students</div><div style="font-weight:700;color:#0F172A;">{{ count($students) }} enrolled</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Mark Attendance</h3>
        <div style="display:flex;gap:8px;">
            <button type="button" onclick="markAll('present')" class="btn-success btn-sm">All Present</button>
            <button type="button" onclick="markAll('absent')"  class="btn-secondary btn-sm">All Absent</button>
        </div>
    </div>
    <form method="POST" action="{{ route('staff.attendance.save', $classSession) }}">
        @csrf
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Student</th><th>Roll Number</th><th>Status</th><th>Remarks</th></tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php $existing = $existingAttendance[$student->id] ?? null; @endphp
                    <tr>
                        <td style="color:#94A3B8;font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:#1D4ED8;">
                                    {{ strtoupper(substr($student->name,0,1)) }}
                                </div>
                                {{ $student->name }}
                            </div>
                        </td>
                        <td style="color:#64748B;font-size:0.8rem;">{{ $student->roll_number ?? '—' }}</td>
                        <td>
                            <select name="attendance[{{ $student->id }}][status]" class="form-select status-select" style="width:130px;">
                                @foreach(['present','absent','late','excused'] as $status)
                                <option value="{{ $status }}" {{ ($existing && $existing->status === $status) ? 'selected' : ($status === 'absent' ? 'selected' : '') }}>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="attendance[{{ $student->id }}][remarks]" class="form-input" style="width:150px;" value="{{ $existing->remarks ?? '' }}" placeholder="Optional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:20px;display:flex;gap:12px;">
            <button type="submit" class="btn-primary">Save Attendance</button>
            <a href="{{ route('staff.attendance.sessions') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.status-select').forEach(sel => sel.value = status);
}
</script>
@endpush
@endsection
