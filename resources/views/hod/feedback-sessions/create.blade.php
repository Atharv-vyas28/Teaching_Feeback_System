@extends('layouts.dashboard')

@section('title', 'Schedule Feedback')

@php
    $header = 'Schedule Feedback';
    $subheader = 'Schedule teaching feedback for this course.';
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
                <h3>Schedule Feedback</h3>

                <p
                    style="
                margin-top:4px;
                color:#64748B;
                font-size:.9rem;
            ">
                    Schedule the feedback session and assign staff members.
                </p>
            </div>

            <a href="{{ route('hod.feedback-sessions.index') }}" class="btn-secondary btn-sm">
                ← Back
            </a>
        </div>


        @if ($errors->any())
            <div
                style="
            background:#FEE2E2;
            color:#991B1B;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                <ul style="margin:0;padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Course Information --}}
        <div
            style="
    margin:20px;
    padding:18px;
    border:1px solid #E2E8F0;
    border-radius:12px;
    background:#F8FAFC;
">
            <div style="
        font-size:.8rem;
        color:#64748B;
        margin-bottom:6px;
    ">
                Course
            </div>

            <div style="
        font-size:1.1rem;
        font-weight:600;
        color:#0F172A;
    ">
                {{ $course->code }}

                <span style="font-weight:400;color:#475569;">
                    — {{ $course->name }}
                </span>
            </div>

            <div style="
        margin-top:8px;
        font-size:.85rem;
        color:#64748B;
    ">
                Feedback will be scheduled for
                <strong style="color:#334155;">
                    {{ $sections->count() }}
                </strong>
                active section(s) of this course.
            </div>
        </div>

        {{-- Schedule Feedback Form --}}

        <form id="feedbackScheduleForm" method="POST"
            action="{{ route('hod.feedback-sessions.store', [
                'semester' => $semester,
                'course' => $course,
            ]) }}"
            style="padding:0 20px 20px;">

            @csrf


            {{-- Feedback Staff --}}
            <div style="margin-bottom:20px;">

                <label
                    style="
            display:block;
            margin-bottom:10px;
            font-weight:600;
            color:#334155;
        ">
                    Feedback Staff
                </label>

                <div
                    style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
        gap:10px;
    ">

                    @forelse($staffMembers as $staff)
                        <label
                            style="
                display:flex;
                align-items:center;
                gap:10px;
                padding:12px 14px;
                border:1px solid #E2E8F0;
                border-radius:8px;
                cursor:pointer;
                background:#FFFFFF;
            ">

                            <input type="checkbox" name="staff_ids[]" value="{{ $staff->id }}"
                                @checked(in_array($staff->id, old('staff_ids', [])))>

                            <span>
                                {{ $staff->name }}
                            </span>

                        </label>

                    @empty

                        <div
                            style="
                color:#64748B;
                font-size:.9rem;
                padding:10px 0;
            ">
                            No active staff members are available for this department.
                        </div>
                    @endforelse

                </div>

                <div style="
        margin-top:7px;
        font-size:.78rem;
        color:#64748B;
    ">
                    Select one or more staff members. They will be able to take attendance
                    for this feedback session.
                </div>

            </div>


            {{-- Start Date & Time --}}
            <div
                style="
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
            gap:16px;
            margin-bottom:20px;
        ">

                <div>

                    <label for="opened_date"
                        style="
        display:block;
        margin-bottom:7px;
        font-weight:600;
        color:#334155;
    ">
                        Start Date
                    </label>

                    <input type="date" id="opened_date" class="form-input" required>

                </div>


                <div>
                    <label
                        style="
            display:block;
            margin-bottom:7px;
            font-weight:600;
            color:#334155;
        ">
                        Start Time
                    </label>

                    <div style="display:flex;align-items:center;gap:8px;">
                        <select id="opened_hour" class="form-input" required>
                            <option value="">Hour</option>

                            @for ($hour = 8; $hour <= 19; $hour++)
                                <option value="{{ sprintf('%02d', $hour) }}">
                                    {{ sprintf('%02d', $hour) }}
                                </option>
                            @endfor
                        </select>

                        <span style="font-weight:600;color:#64748B;">:</span>

                        <select id="opened_minute" class="form-input" required>
                            <option value="">Minute</option>

                            @for ($minute = 0; $minute < 60; $minute += 5)
                                <option value="{{ sprintf('%02d', $minute) }}">
                                    {{ sprintf('%02d', $minute) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

            </div>


            {{-- Hidden combined datetime --}}
            <input type="hidden" name="opened_at" id="opened_at" value="{{ old('opened_at') }}">


            {{-- Duration --}}
            <div style="margin-bottom:24px;">

                <label
                    style="
                    display:block;
                    margin-bottom:10px;
                    font-weight:600;
                    color:#334155;
                ">
                    Feedback Duration
                </label>

                <div
                    style="
                display:grid;
                grid-template-columns:repeat(auto-fit, minmax(120px, 1fr));
                gap:10px;
            ">

                    @foreach ([30, 60, 90, 120] as $duration)
                        <label
                            style="
                            display:flex;
                            align-items:center;
                            gap:8px;
                            padding:12px 14px;
                            border:1px solid #E2E8F0;
                            border-radius:8px;
                            cursor:pointer;
                            background:#FFFFFF;
                        ">

                            <input type="radio" name="duration_minutes" value="{{ $duration }}"
                                @checked(old('duration_minutes', 60) == $duration) required>

                            <span>
                                {{ $duration }} minutes
                            </span>

                        </label>
                    @endforeach

                </div>

                <div
                    style="
                margin-top:7px;
                font-size:.78rem;
                color:#64748B;
            ">
                    The feedback session will automatically end after the selected duration.
                </div>

            </div>


            {{-- Submit --}}
            <div style="
            display:flex;
            justify-content:flex-end;
            gap:10px;
        ">

                <a href="{{ route('hod.feedback-sessions.index') }}" class="btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    Schedule Feedback
                </button>

            </div>

        </form>

    </div>


    {{-- Combine date + time into Scheduleed_at --}}
    <script>
        const form = document.getElementById('feedbackScheduleForm');

        const dateInput = document.getElementById('opened_date');
        const hourInput = document.getElementById('opened_hour');
        const minuteInput = document.getElementById('opened_minute');
        const openedAtInput = document.getElementById('opened_at');

        function updateOpenedAt() {
            if (dateInput.value && hourInput.value && minuteInput.value) {
                openedAtInput.value =
                    dateInput.value + ' ' +
                    hourInput.value + ':' +
                    minuteInput.value;
            } else {
                openedAtInput.value = '';
            }
        }

        dateInput.addEventListener('change', updateOpenedAt);
        hourInput.addEventListener('change', updateOpenedAt);
        minuteInput.addEventListener('change', updateOpenedAt);

        form.addEventListener('submit', function(event) {
            updateOpenedAt();

            const selectedStaff = document.querySelectorAll(
                'input[name="staff_ids[]"]:checked'
            );

            if (selectedStaff.length === 0) {
                event.preventDefault();
                alert('Please select at least one feedback staff member.');
                return;
            }

            if (!openedAtInput.value) {
                event.preventDefault();
                alert('Please select the start date, hour, and minute.');
            }
        });
    </script>

@endsection
