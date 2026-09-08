@extends('layouts.dashboard')

@section('title', 'My Ratings')

@php
    $header = 'My Performance Ratings';
    $subheader = 'Aggregated anonymous feedback released by the administrator.';
@endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>

<a href="{{ route('faculty.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 00-2-2M9 5a2 2 0 012-2h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Courses
</a>

<a href="{{ route('faculty.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add Lecture
</a>

<div class="nav-section-label">Feedback</div>

<a href="{{ route('faculty.feedback.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
    Feedback Sessions
</a>

<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
    </svg>
    My Ratings
</a>
@endsection

@section('content')
@if($ratingResults->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center;padding:60px;color:#94A3B8;">
            <p>
                No released feedback ratings are available yet.
                Ratings appear after an administrator releases feedback.
            </p>
        </div>
    </div>
@else
    <div style="display:grid;gap:16px;">
        @foreach($ratingResults as $r)

            @php
                /*
                 * RatingResult casts question_averages to an array.
                 * This supports both new array data and old JSON-string data safely.
                 */
                $questionAverages = $r->question_averages ?? [];

                if (is_string($questionAverages)) {
                    $questionAverages = json_decode(
                        $questionAverages,
                        true
                    );
                }

                if (! is_array($questionAverages)) {
                    $questionAverages = [];
                }

                $overallRating = (float) ($r->overall_weighted_rating ?? 0);

                $status = $overallRating >= 4
                    ? ['Excellent', 'badge-green']
                    : ($overallRating >= 3
                        ? ['Stable', 'badge-yellow']
                        : ['Needs Improvement', 'badge-red']);
            @endphp

            <div class="card">
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
                        <div>
                            <div style="font-size:1rem;font-weight:700;color:#0F172A;">
                                {{ $r->section?->course?->name ?? 'Course unavailable' }}
                            </div>

                            <div style="font-size:0.8rem;color:#64748B;margin-top:2px;">
                                {{ $r->semester?->name ?? 'Semester unavailable' }}
                                ·
                                {{ $r->response_count ?? 0 }} responses
                            </div>

                            <div style="font-size:0.75rem;color:#94A3B8;margin-top:2px;">
                                Calculated:
                                {{ $r->calculated_at?->format('M d, Y H:i') ?? 'N/A' }}
                            </div>
                        </div>

                        <div style="text-align:right;">
                            @if($overallRating > 0)
                                <div style="font-size:2.4rem;font-weight:900;color:{{ $overallRating >= 4 ? '#1D4ED8' : ($overallRating >= 3 ? '#D97706' : '#DC2626') }};letter-spacing:-0.03em;line-height:1;">
                                    {{ number_format($overallRating, 1) }}
                                </div>

                                <div style="font-size:0.75rem;color:#94A3B8;">
                                    out of 5.0
                                </div>

                                <span class="badge {{ $status[1] }}" style="margin-top:6px;">
                                    {{ $status[0] }}
                                </span>
                            @else
                                <span style="color:#94A3B8;">Pending</span>
                            @endif
                        </div>
                    </div>

                    @if(! empty($questionAverages))
                        <div style="margin-top:16px;padding-top:16px;border-top:1px solid #F1F5F9;">
                            @foreach($questionAverages as $qa)

                                @php
                                    $average = (float) ($qa['average'] ?? 0);
                                    $percentage = max(
                                        0,
                                        min(100, ($average / 5) * 100)
                                    );
                                @endphp

                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                    <div style="font-size:0.8rem;color:#374151;flex:1;">
                                        {{ $qa['question'] ?? 'Question' }}
                                    </div>

                                    <div style="width:120px;background:#F1F5F9;border-radius:100px;height:6px;overflow:hidden;">
                                        <div style="height:100%;width:{{ $percentage }}%;background:linear-gradient(135deg,#3B82F6,#2563EB);border-radius:100px;"></div>
                                    </div>

                                    <span style="font-size:0.82rem;font-weight:700;color:#0F172A;min-width:30px;text-align:right;">
                                        {{ number_format($average, 1) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection