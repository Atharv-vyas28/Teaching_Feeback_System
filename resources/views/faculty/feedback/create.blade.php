@extends('layouts.dashboard')
@section('title', 'Create Feedback Session')
@php $header = 'Create Feedback Session'; $subheader = 'Create a feedback session for this class.'; @endphp

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
<div class="nav-section-label">Feedback</div>
<a href="{{ route('faculty.feedback.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Add Lecture
</a>
@endsection

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header"><h3>Feedback Session Details</h3></div>
    <div class="card-body">
        <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:16px;margin-bottom:20px;">
            <div style="font-size:0.8rem;font-weight:600;color:#1E40AF;margin-bottom:6px;">Session Information</div>
            <div style="font-size:0.85rem;color:#374151;"><strong>Course:</strong> {{ $classSession->section->course->name ?? 'N/A' }}</div>
            <div style="font-size:0.85rem;color:#374151;"><strong>Date:</strong> {{ $classSession->session_date->format('M d, Y') }}</div>
            <div style="font-size:0.85rem;color:#374151;"><strong>Topic:</strong> {{ $classSession->topic ?? 'N/A' }}</div>
            <div style="font-size:0.85rem;color:#374151;"><strong>Status:</strong> {{ ucfirst($classSession->status) }}</div>
        </div>

        <p style="font-size:0.85rem;color:#64748B;margin-bottom:20px;">
            This will create a feedback session in <strong>draft</strong> status. You can open it for students after creating it.
            Only students marked as <strong>present</strong> with feedback enabled will be eligible to submit.
        </p>

        <form method="POST" action="{{ route('faculty.feedback.store', $classSession) }}">
            @csrf
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn-primary">Create Feedback Session</button>
                <a href="{{ route('faculty.attendance.sessions') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
