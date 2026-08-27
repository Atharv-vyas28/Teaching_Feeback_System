@extends('layouts.dashboard')
@section('title', 'Feedback Submitted')
@php $header = 'Thank You!'; $subheader = 'Your feedback has been submitted anonymously.'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('student.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Courses</div>
<a href="{{ route('student.attendance') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Attendance
</a>
<div class="nav-section-label">Feedback</div>
<a href="{{ route('student.feedback.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Submit Feedback
</a>
@endsection

@section('content')
<div style="max-width:520px;margin:40px auto;text-align:center;">
    <div style="width:90px;height:90px;background:linear-gradient(135deg,#DCFCE7,#BBF7D0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
        <svg width="44" height="44" fill="none" stroke="#15803D" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h1 style="font-size:1.6rem;font-weight:800;color:#0F172A;margin-bottom:10px;letter-spacing:-0.02em;">Feedback Submitted!</h1>
    <p style="font-size:0.9rem;color:#64748B;line-height:1.6;margin-bottom:8px;">
        Your feedback for <strong>{{ $feedbackSession->classSession->section->course->name ?? 'this course' }}</strong> has been recorded anonymously.
    </p>
    <p style="font-size:0.82rem;color:#94A3B8;margin-bottom:32px;">
        🔒 Your identity is never linked to your response. Thank you for helping improve teaching quality!
    </p>

    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:14px;padding:20px;margin-bottom:28px;text-align:left;">
        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:#94A3B8;margin-bottom:10px;">Session Details</div>
        <div style="display:flex;flex-direction:column;gap:6px;">
            <div style="font-size:0.85rem;color:#374151;"><strong>Course:</strong> {{ $feedbackSession->classSession->section->course->name ?? 'N/A' }}</div>
            <div style="font-size:0.85rem;color:#374151;"><strong>Date:</strong> {{ $feedbackSession->classSession->session_date->format('M d, Y') }}</div>
            @if($feedbackSession->classSession->topic)
            <div style="font-size:0.85rem;color:#374151;"><strong>Topic:</strong> {{ $feedbackSession->classSession->topic }}</div>
            @endif
        </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('student.feedback.index') }}" class="btn-primary">View All Feedback</a>
        <a href="{{ route('student.dashboard') }}" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection
