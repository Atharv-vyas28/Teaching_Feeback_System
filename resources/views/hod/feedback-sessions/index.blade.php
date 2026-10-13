@extends('layouts.dashboard')

@section('title', 'Feedback Sessions')

@php
    $header = 'Feedback Sessions';
    $subheader = 'Schedule and monitor feedback for your department.';
@endphp

@section('sidebar-nav')
    <div class="nav-section-label">Main</div>
    <a href="{{ route('faculty.dashboard') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>
    <div class="nav-section-label">Attendance</div>
    <a href="{{ route('faculty.attendance.sessions') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Courses
    </a>
    <a href="{{ route('faculty.attendance.create') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Lecture
    </a>
    <div class="nav-section-label">Feedback</div>
    @if (auth()->user()->is_head)
        <a href="{{ route('hod.feedback-sessions.index') }}" class="nav-link active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            Feedback Sessions
        </a>
    @endif
    <a href="{{ route('faculty.feedback.index') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        My Feedbacks
    </a>
    <a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
        </svg>
        My Ratings
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <h3>Feedback Sessions</h3>

                <p style="margin-top:4px;color:#64748B;font-size:.9rem;">
                    Open feedback courses are shown first.
                </p>
            </div>
        </div>

        <div style="padding:20px;display:flex;flex-direction:column;gap:12px;">
            @forelse ($courses as $course)
                <div
                    style="
                    border:1px solid #E2E8F0;
                    border-radius:12px;
                    padding:18px;
                    background:#FFFFFF;
                ">
                    <div
                        style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                        gap:16px;
                        flex-wrap:wrap;
                    ">
                        <div>
                            <div style="font-size:1rem;font-weight:600;color:#0F172A;">
                                {{ $course->code }}

                                <span style="font-weight:400;color:#475569;">
                                    — {{ $course->name }}
                                </span>
                            </div>

                            <div style="margin-top:6px;font-size:.82rem;color:#64748B;">
                                {{ $course->sections_count }} section(s)
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;">
                            @if ($course->is_done)
                                <span class="badge badge-green">
                                    Feedback Completed
                                </span>
                            @else
                                <a href="{{ route('hod.feedback-sessions.create', [
                                    'semester' => $semester,
                                    'course' => $course->id,
                                ]) }}"
                                    class="btn-primary btn-sm">
                                    Set Feedback Schedule
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding:40px;text-align:center;color:#64748B;">
                    No feedback courses are currently open for your department.
                </div>
            @endforelse
        </div>
    </div>
@endsection
