@extends('layouts.dashboard')

@section('title', 'Assigned Feedback')
@php
    $header = 'Assigned Feedback';
    $subheader = 'Feedback sessions assigned by the administrator.';
@endphp

@section('sidebar-nav')
@include('staff.partials.sidebar')
<!--
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link">Dashboard</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link">My Sessions</a>
<a href="{{ route('staff.attendance.history') }}" class="nav-link">Attendance History</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('staff.feedback.index') }}" class="nav-link active">Assigned Feedback</a>
-->
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3>My Assigned Feedback Sessions</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Course</th><th>Branch</th><th>Section</th><th>Class Date</th><th>Status</th><th>Manage</th></tr></thead>
            <tbody>
            @forelse($feedbackSessions as $feedbackSession)
                @php
                    $isExpired = $feedbackSession->status === 'active' && $feedbackSession->deadline_at && $feedbackSession->deadline_at < now();
                    $badgeColor = 'gray';
                    $statusLabel = ucfirst($feedbackSession->status);
                    if ($feedbackSession->status === 'active') {
                        $badgeColor = $isExpired ? 'red' : 'green';
                        $statusLabel = $isExpired ? 'Expired' : 'Active';
                    } elseif ($feedbackSession->status === 'draft') {
                        $badgeColor = 'yellow';
                    } elseif ($feedbackSession->status === 'closed') {
                        $badgeColor = 'blue';
                    }
                @endphp
                <tr>
                    <td><strong>{{ $feedbackSession->classSession->section->course->name ?? '—' }}</strong><br><small>{{ $feedbackSession->classSession->section->course->code ?? '' }}</small></td>
                    <td>{{ $feedbackSession->classSession->section->course->department->name ?? '—' }}</td>
                    <td>{{ $feedbackSession->classSession->section->section_name ?? '—' }}</td>
                    <td>{{ $feedbackSession->classSession->session_date?->format('d M Y') ?? '—' }}</td>
                    <td><span class="badge badge-{{ $badgeColor }}">{{ $statusLabel }}</span></td>
                    <td>
                        <a href="{{ route('staff.feedback.attendance.take', $feedbackSession) }}" class="btn-secondary btn-sm" style="margin-bottom:7px;">Mark Feedback-Day Attendance</a>
                        @if($feedbackSession->status === 'draft')
                            <div style="font-size:.76rem;color:#64748B;margin-bottom:7px;">Admin window:<br>{{ $feedbackSession->release_at?->format('d M, h:i A') ?? 'Not configured' }} → {{ $feedbackSession->deadline_at?->format('d M, h:i A') ?? 'Not configured' }}</div>
                            @if($feedbackSession->release_at && $feedbackSession->deadline_at && now()->between($feedbackSession->release_at, $feedbackSession->deadline_at))
                                <form method="POST" action="{{ route('staff.feedback.release', $feedbackSession) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary btn-sm">Start Feedback</button>
                                </form>
                            @else
                                <span style="font-size:.76rem;color:#94A3B8;">Waiting for the Admin release window.</span>
                            @endif
                        @elseif($feedbackSession->status === 'active')
                            <div style="font-size:.78rem;color:{{ $isExpired ? '#DC2626' : '#64748B' }};margin-bottom:7px; font-weight: {{ $isExpired ? '600' : 'normal' }};">
                                Ends: {{ $feedbackSession->deadline_at?->format('d M, h:i A') ?? 'No deadline' }}
                            </div>
                            <form method="POST" action="{{ route('staff.feedback.close', $feedbackSession) }}" onsubmit="return confirm('Close this feedback session?')">
                                @csrf
                                <button type="submit" class="btn-secondary btn-sm">Close Feedback</button>
                            </form>
                        @else
                             <div style="font-size:.78rem;color:#64748B;">
                                Closed on {{ $feedbackSession->closed_at?->format('d M, h:i A') ?? '—' }}
                            </div>
                        @endif
                    </td>
                </tr>

            @empty
                <tr><td colspan="6" style="text-align:center;padding:34px;color:#64748B;">No feedback sessions have been assigned to you.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px;">{{ $feedbackSessions->links() }}</div>
</div>
@endsection
