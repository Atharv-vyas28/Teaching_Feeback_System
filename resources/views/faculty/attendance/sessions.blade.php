@extends('layouts.dashboard')
@section('title', 'My Sessions')
@php $header = 'Class Sessions'; $subheader = 'All class sessions you have conducted.'; @endphp

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

@section('page-actions')
<a href="{{ route('faculty.attendance.create') }}" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
@endsection

@section('content')
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course / Section</th>
                    <th>Topic</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $session->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">Section {{ $session->section->section_name ?? '' }}</div>
                    </td>
                    <td>{{ $session->topic ?? '—' }}</td>
                    <td>{{ $session->session_date->format('M d, Y') }}</td>
                    <td style="font-size:0.8rem;color:#64748B;">{{ $session->start_time }} – {{ $session->end_time }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $session->attendanceRecords->count() }} present</span>
                    </td>
                    <td><span class="badge {{ ['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray' }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if($session->feedbackSession)
                            <span class="badge badge-{{ ['draft'=>'gray','active'=>'green','closed'=>'blue'][$session->feedbackSession->status] ?? 'gray' }}">{{ ucfirst($session->feedbackSession->status) }}</span>
                        @else
                            <a href="{{ route('faculty.feedback.create', $session) }}" style="font-size:0.78rem;color:#3B82F6;text-decoration:none;">Create →</a>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            @if($session->status === 'ongoing')
                            <a href="{{ route('faculty.attendance.take', $session) }}" class="btn-primary btn-sm">Take Attendance</a>
                            @elseif($session->status === 'completed')
                            <a href="{{ route('faculty.attendance.take', $session) }}" class="btn-secondary btn-sm">View</a>
                            @endif
                            @if($session->feedbackSession && $session->feedbackSession->status !== 'draft')
                            <a href="{{ route('faculty.feedback.analytics', $session->feedbackSession) }}" class="btn-secondary btn-sm">Analytics</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:#94A3B8;">
                        No sessions yet. <a href="{{ route('faculty.attendance.create') }}" style="color:#3B82F6;">Create your first session →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sessions->hasPages())
    <div style="padding:16px 20px;">{{ $sessions->links() }}</div>
    @endif
</div>
@endsection
