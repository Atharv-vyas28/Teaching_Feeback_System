@extends('layouts.dashboard')
@section('title', 'Staff Dashboard')
@php $header = 'Staff Dashboard'; $subheader = 'Manage class sessions and attendance for your assigned sections.'; @endphp

@section('sidebar-nav')
@include('staff.partials.sidebar')
{{-- Legacy links below are disabled; all Staff pages use the partial above. --}}
<!--
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Courses
</a>
<a href="{{ route('staff.attendance.history') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Attendance History
</a>
<a href="{{ route('staff.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('staff.feedback.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Assigned Feedback
</a>
-->
@endsection

@section('content')
{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    @php
    $statItems = [
        ['label'=>'Assigned Sections', 'value'=>$stats['sections'],        'color'=>'background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#1D4ED8',  'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label'=>'Total Sessions',    'value'=>$stats['total_sessions'],  'color'=>'background:linear-gradient(135deg,#EDE9FE,#DDD6FE);color:#6D28D9',  'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>"Today's Sessions",  'value'=>$stats['today_sessions'],  'color'=>'background:linear-gradient(135deg,#FEF9C3,#FDE68A);color:#92400E',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Active Feedback',   'value'=>$stats['active_feedback'], 'color'=>'background:linear-gradient(135deg,#DCFCE7,#BBF7D0);color:#15803D',  'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['label'=>'Closed Feedback',   'value'=>$stats['closed_feedback'], 'color'=>'background:linear-gradient(135deg,#F1F5F9,#E2E8F0);color:#475569',  'icon'=>'M5 13l4 4L19 7'],
        ['label'=>'Expired Feedback',  'value'=>$stats['expired_feedback'], 'color'=>'background:linear-gradient(135deg,#FEE2E2,#FECACA);color:#DC2626',  'icon'=>'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    @endphp
    @foreach($statItems as $s)
    <div class="stat-card">
        <div class="stat-icon" style="{{ $s['color'] }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div class="stat-label">{{ $s['label'] }}</div>
        <div class="stat-value">{{ $s['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Recent Sessions --}}
<div class="card">
    <div class="card-header">
        <h3>Recent Sessions</h3>
        <a href="{{ route('staff.attendance.create') }}" class="btn-primary btn-sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Session
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>Course</th><th>Date</th><th>Present</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($recentSessions as $session)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $session->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $session->topic ?? 'No topic' }}</div>
                    </td>
                    <td>{{ $session->session_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-blue">
                            {{ $session->attendanceRecords->whereIn('status',['present','late'])->count() }} / {{ $session->attendanceRecords->count() }}
                        </span>
                    </td>
                    <td><span class="badge {{ ['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray' }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if(in_array($session->status, ['ongoing','scheduled']))
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-primary btn-sm">Take Attendance</a>
                        @else
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-secondary btn-sm">View</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;color:#94A3B8;">No sessions yet. <a href="{{ route('staff.attendance.create') }}" style="color:#3B82F6;">Create one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Active Feedback Sessions --}}
@if($activeFeedbackSessions->count() > 0)
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>Active Feedback Sessions</h3>
        <a href="{{ route('staff.feedback.index') }}" class="btn-secondary btn-sm">View All</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Release Time</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeFeedbackSessions as $fs)
                @php
                    $isExpired = $fs->deadline_at && $fs->deadline_at < now();
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $fs->classSession->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">Section {{ $fs->classSession->section->section_name ?? '—' }}</div>
                    </td>
                    <td>{{ $fs->release_at?->format('M d, h:i A') ?? '—' }}</td>
                    <td>
                        @if($isExpired)
                            <span style="color:#DC2626;font-weight:600;">{{ $fs->deadline_at->format('M d, h:i A') }} (Expired)</span>
                        @else
                            {{ $fs->deadline_at?->format('M d, h:i A') ?? '—' }}
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('staff.feedback.close', $fs) }}" onsubmit="return confirm('Close this feedback session?')">
                            @csrf
                            <button type="submit" class="btn-secondary btn-sm">Close Feedback</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($currentSemester)
<div style="margin-top:16px;padding:14px 18px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;font-size:0.83rem;color:#1E40AF;">
    📅 <strong>Current Semester:</strong> {{ $currentSemester->name }} ({{ $currentSemester->start_date->format('M d') }} – {{ $currentSemester->end_date->format('M d, Y') }})
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driver = window.driver.js.driver;
    const tour = driver({
        showProgress: true,
        animate: true,
        steps: [
            { element: '.sidebar-brand', popover: { title: 'Welcome to SmartPulse!', description: 'This is your staff dashboard.', side: 'right', align: 'start' } },
            { element: 'a[href="{{ route("staff.attendance.create") }}"]', popover: { title: 'New Class Session', description: 'Help faculty by starting class sessions and marking attendance.', side: 'right', align: 'start' } },
            { element: 'a[href="{{ route("staff.attendance.sessions") }}"]', popover: { title: 'Manage Sessions', description: 'View and edit previous class sessions you managed.', side: 'right', align: 'start' } },
            { element: '.card', popover: { title: 'Recent Activity', description: 'See the latest sessions conducted.', side: 'top', align: 'start' } },
        ]
    });

    const startBtn = document.getElementById('start-tour-btn');
    if (startBtn) {
        startBtn.addEventListener('click', () => tour.drive());
    }

    if (!localStorage.getItem('tourCompleted_staff')) {
        setTimeout(() => tour.drive(), 500);
        localStorage.setItem('tourCompleted_staff', 'true');
    }
});
</script>
@endpush
@endsection
