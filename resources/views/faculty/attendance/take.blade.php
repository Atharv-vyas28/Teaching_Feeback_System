@extends('layouts.dashboard')
@section('title', 'Take Attendance')
@php $header = 'Take Attendance'; $subheader = $classSession->section->course->name ?? 'Class Session'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('faculty.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<a href="{{ route('faculty.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('faculty.feedback.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Feedback Sessions
</a>
<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    My Ratings
</a>
@endsection

@section('content')
{{-- Session Info --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Course</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->section->course->name ?? 'N/A' }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Date</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->session_date->format('M d, Y') }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Time</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->start_time }} – {{ $classSession->end_time }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Topic</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->topic ?? 'N/A' }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Students</div><div style="font-weight:700;color:#0F172A;">{{ count($students) }} enrolled</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Mark Attendance</h3>
        <div style="display:flex;gap:8px;">
            <button type="button" onclick="markAll('present')" class="btn-success btn-sm">Mark All Present</button>
            <button type="button" onclick="markAll('absent')" class="btn-secondary btn-sm">Mark All Absent</button>
        </div>
    </div>
    <form method="POST" action="{{ route('faculty.attendance.save', $classSession) }}">
        @csrf
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Roll Number</th>
                        <th>Status</th>
                        <th>Feedback Access</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php $existing = $existingAttendance[$student->id] ?? null; @endphp
                    <tr>
                        <td style="color:#94A3B8;font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:#1D4ED8;flex-shrink:0;">
                                    {{ strtoupper(substr($student->name,0,1)) }}
                                </div>
                                {{ $student->name }}
                            </div>
                        </td>
                        <td style="color:#64748B;font-size:0.8rem;">{{ $student->roll_number ?? '—' }}</td>
                        <td>
                            <select name="attendance[{{ $student->id }}][status]" class="form-select status-select" style="width:130px;" data-student="{{ $student->id }}">
                                @foreach(['present','absent','late','excused'] as $status)
                                <option value="{{ $status }}" {{ ($existing && $existing->status === $status) ? 'selected' : ($status === 'absent' ? 'selected' : '') }}>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                                <input type="checkbox"
                                       name="attendance[{{ $student->id }}][feedback_enabled]"
                                       value="1"
                                       class="feedback-cb cb-{{ $student->id }}"
                                       {{ ($existing && $existing->feedback_enabled) ? 'checked' : '' }}
                                       style="width:16px;height:16px;accent-color:#3B82F6;">
                                <span style="font-size:0.8rem;color:#64748B;">Enable</span>
                            </label>
                        </td>
                        <td>
                            <input type="text"
                                   name="attendance[{{ $student->id }}][remarks]"
                                   class="form-input"
                                   style="width:150px;"
                                   value="{{ $existing->remarks ?? '' }}"
                                   placeholder="Optional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:20px;display:flex;gap:12px;">
            <button type="submit" class="btn-primary">Save Attendance</button>
            <a href="{{ route('faculty.attendance.sessions') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.status-select').forEach(sel => {
        sel.value = status;
        // Auto-enable feedback for present/late
        const studentId = sel.dataset.student;
        const cb = document.querySelector('.cb-' + studentId);
        if (cb) cb.checked = (status === 'present' || status === 'late');
    });
}

// Auto-toggle feedback checkbox based on status
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const studentId = this.dataset.student;
        const cb = document.querySelector('.cb-' + studentId);
        if (cb) {
            if (this.value === 'present' || this.value === 'late') {
                cb.disabled = false;
            } else {
                cb.checked = false;
                cb.disabled = true;
            }
        }
    });
    // Initialize state
    sel.dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection
