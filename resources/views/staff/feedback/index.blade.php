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
<a href="{{ route('staff.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<a href="{{ route('staff.feedback.index') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Assigned Feedback
</a>
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
                        @if($feedbackSession->status === 'draft')
                            <form method="POST" action="{{ route('staff.feedback.release', $feedbackSession) }}" style="display:grid;gap:6px;min-width:210px;">
                                @csrf
                                <input type="datetime-local" name="release_at" class="form-input" required>
                                <input type="datetime-local" name="deadline_at" class="form-input" required>
                                <button type="submit" class="btn-primary btn-sm">Release Feedback</button>
                            </form>
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
