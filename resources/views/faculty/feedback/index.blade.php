@extends('layouts.dashboard')
@section('title', 'Feedback Sessions')
@php $header = 'Feedback Sessions'; $subheader = 'Manage student feedback for your class sessions.'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('faculty.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Courses
</a>
<a href="{{ route('faculty.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('faculty.feedback.index') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Feedback Sessions
</a>
<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    My Ratings
</a>
@endsection

@section('content')
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course / Session</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Responses</th>
                    <th>Eligible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbackSessions as $fb)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $fb->classSession->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $fb->classSession->session_date->format('M d, Y') ?? '' }} · {{ $fb->classSession->topic ?? 'No topic' }}</div>
                    </td>
                    <td>{{ $fb->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ ['draft'=>'gray','active'=>'green','closed'=>'blue'][$fb->status] ?? 'gray' }}">
                            {{ ucfirst($fb->status) }}
                        </span>
                    </td>
                    <td>
                        {{ $fb->isReleased() ? $fb->responses_count : '—' }}
                    </td>
                    <td>{{ $fb->eligibility->count() }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            @if($fb->status === 'draft')
                            <form method="POST" action="{{ route('faculty.feedback.open', $fb) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-success btn-sm">Open</button>
                            </form>
                            @elseif($fb->status === 'active')
                            <form method="POST" action="{{ route('faculty.feedback.close', $fb) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-warning btn-sm">Close</button>
                            </form>
                            @endif
                             @if($fb->isReleased())
                            <a href="{{ route('faculty.feedback.analytics', $fb) }}" class="btn-secondary btn-sm">
                                    Analytics
                            </a>
                            @elseif($fb->status === 'closed')
                                <span class="badge badge-yellow">Awaiting admin release</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">
                        No feedback sessions yet. Create one from the <a href="{{ route('faculty.attendance.sessions') }}" style="color:#3B82F6;">Sessions</a> page.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($feedbackSessions->hasPages())
    <div style="padding:16px 20px;">{{ $feedbackSessions->links() }}</div>
    @endif
</div>
@endsection
