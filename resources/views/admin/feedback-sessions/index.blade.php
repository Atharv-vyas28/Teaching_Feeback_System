@extends('layouts.dashboard')

@section('title', 'Feedback Sessions')

@php
    $header = 'Feedback Sessions';

    if (($level ?? 'semester') === 'semester') {
        $subheader = 'Select a semester to manage course feedback.';
    } elseif (($level ?? '') === 'department') {
        $subheader = 'Select a department to view its courses.';
    } else {
        $subheader = 'Manage feedback sessions for courses.';
    }
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')

    <div class="card">

        {{-- Header --}}
        <div class="card-header">

            <div>
                <h3>Feedback Sessions</h3>

                <p style="margin-top:4px;color:#64748B;font-size:.9rem;">
                    {{ $subheader }}
                </p>
            </div>

            @if (($level ?? 'semester') === 'department')
                <a href="{{ route('admin.feedback-sessions.index') }}" class="btn-secondary btn-sm">
                    ← Semesters
                </a>
            @elseif(($level ?? '') === 'course')
                <a href="{{ route('admin.feedback-sessions.departments', $semester) }}" class="btn-secondary btn-sm">
                    ← Departments
                </a>
            @elseif(($level ?? '') === 'faculty')
                <a href="{{ route('admin.feedback-sessions.courses', [
                    'semester' => $semester,
                    'department' => $department,
                ]) }}"
                    class="btn-secondary btn-sm">
                    ← Courses
                </a>
            @endif

        </div>


        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                style="
            background:#DCFCE7;
            color:#166534;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                style="
            background:#FEE2E2;
            color:#991B1B;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
         LEVEL 1 : SEMESTERS
    ========================================================== --}}
        @if (($level ?? 'semester') === 'semester')

            <div
                style="
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
            gap:16px;
            padding:20px;
        ">

                @forelse($semesters as $semester)
                    <a href="{{ route('admin.feedback-sessions.departments', $semester) }}"
                        style="
                        display:block;
                        text-decoration:none;
                        color:inherit;
                        border:1px solid #E2E8F0;
                        border-radius:12px;
                        padding:20px;
                        background:#FFFFFF;
                        transition:all .2s ease;
                    "
                        onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">

                        <div
                            style="
                        display:flex;
                        justify-content:space-between;
                        align-items:flex-start;
                        gap:12px;
                    ">

                            <div>

                                <div
                                    style="
                                font-size:1.05rem;
                                font-weight:600;
                                color:#0F172A;
                            ">
                                    {{ $semester->name }}
                                </div>

                                <div
                                    style="
                                margin-top:5px;
                                font-size:.82rem;
                                color:#64748B;
                            ">
                                    Academic Year:
                                    {{ $semester->academicYear?->name ?? 'N/A' }}
                                </div>

                            </div>

                            @if ($semester->is_current)
                                <span class="badge badge-green">
                                    Current
                                </span>
                            @endif

                        </div>

                    </a>

                @empty

                    <div
                        style="
                    grid-column:1/-1;
                    text-align:center;
                    padding:40px 20px;
                    color:#64748B;
                ">
                        No semesters found.
                    </div>
                @endforelse

            </div>


            {{-- =========================================================
         LEVEL 2 : DEPARTMENTS
    ========================================================== --}}
        @elseif(($level ?? '') === 'department')
            <div style="padding:20px;">

                {{-- Breadcrumb --}}
                <div
                    style="
                margin-bottom:18px;
                font-size:.85rem;
                color:#64748B;
            ">
                    <a href="{{ route('admin.feedback-sessions.index') }}" style="color:#0F172A;text-decoration:none;">
                        Semesters
                    </a>

                    <span style="margin:0 6px;">/</span>
                    <strong style="color:#0F172A;">
                        {{ $semester->name }}
                    </strong>
                </div>


                <div
                    style="
                display:grid;
                grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
                gap:16px;
            ">

                    @forelse($departments as $department)
                        <a href="{{ route('admin.feedback-sessions.courses', [
                            'semester' => $semester,
                            'department' => $department,
                        ]) }}"
                            style="
                            display:block;
                            text-decoration:none;
                            color:inherit;
                            border:1px solid #E2E8F0;
                            border-radius:12px;
                            padding:20px;
                            background:#FFFFFF;
                            transition:all .2s ease;
                        "
                            onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">

                            <div
                                style="
                            font-size:1.05rem;
                            font-weight:600;
                            color:#0F172A;
                        ">
                                {{ $department->name }}
                            </div>

                            <div
                                style="
                            margin-top:8px;
                            font-size:.85rem;
                            color:#64748B;
                        ">
                                {{ $department->semester_courses_count ?? 0 }}
                                courses
                            </div>

                        </a>

                    @empty

                        <div
                            style="
                        grid-column:1/-1;
                        text-align:center;
                        padding:40px 20px;
                        color:#64748B;
                    ">
                            No departments found for this semester.
                        </div>
                    @endforelse

                </div>

            </div>


            {{-- =========================================================
         LEVEL 3 : COURSES
    ========================================================== --}}
        @elseif(($level ?? '') === 'course')
            <div style="padding:20px;">

                {{-- Breadcrumb --}}
                <div
                    style="
            margin-bottom:20px;
            font-size:.85rem;
            color:#64748B;
        ">
                    <a href="{{ route('admin.feedback-sessions.index') }}" style="color:#0F172A;text-decoration:none;">
                        Semesters
                    </a>

                    <span style="margin:0 6px;">/</span>

                    <a href="{{ route('admin.feedback-sessions.departments', $semester) }}"
                        style="color:#0F172A;text-decoration:none;">
                        {{ $semester->name }}
                    </a>

                    <span style="margin:0 6px;">/</span>

                    <strong style="color:#0F172A;">
                        {{ $department->name }}
                    </strong>
                </div>


                {{-- Courses --}}
                <div style="
            display:flex;
            flex-direction:column;
            gap:12px;
        ">

                    @forelse($courses as $course)
                        <a href="{{ route('admin.feedback-sessions.faculty', [
                            'semester' => $semester,
                            'department' => $department,
                            'course' => $course->id,
                        ]) }}"
                            style="
                            display:block;
                            text-decoration:none;
                            color:inherit;
                            border:1px solid #E2E8F0;
                            border-radius:12px;
                            padding:20px;
                            background:#FFFFFF;
                            transition:all .2s ease;
                        "
                            onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">


                            <div
                                style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:20px;
                        flex-wrap:wrap;
                    ">

                                {{-- Course Information --}}
                                <div>

                                    <div
                                        style="
                                font-weight:600;
                                font-size:1rem;
                                color:#0F172A;
                            ">
                                        {{ $course->code }}

                                        <span
                                            style="
                                    font-weight:400;
                                    color:#475569;
                                ">
                                            — {{ $course->name }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div
                            style="
                    text-align:center;
                    padding:40px 20px;
                    color:#64748B;
                ">
                            No courses found for this department.
                        </div>
                    @endforelse

                </div>

            </div>
        @elseif(($level ?? '') === 'faculty')
            <div style="padding:20px;">

                {{-- =========================================================
             Breadcrumb
        ========================================================== --}}
                <div
                    style="
                display:flex;
                align-items:center;
                flex-wrap:wrap;
                gap:6px;
                margin-bottom:22px;
                font-size:.85rem;
                color:#64748B;
            ">

                    <a href="{{ route('admin.feedback-sessions.index') }}"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        Semesters
                    </a>

                    <span>/</span>

                    <a href="{{ route('admin.feedback-sessions.departments', $semester) }}"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        {{ $semester->name }}
                    </a>

                    <span>/</span>

                    <a href="{{ route('admin.feedback-sessions.courses', [
                        'semester' => $semester,
                        'department' => $department,
                    ]) }}"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        {{ $department->name }}
                    </a>

                    <span>/</span>

                    <strong style="color:#0F172A;">
                        {{ $course->code }}
                    </strong>

                </div>


                {{-- =========================================================
             Course Header
        ========================================================== --}}
                <div style="
                margin-bottom:22px;
            ">

                    <div
                        style="
                    display:flex;
                    justify-content:space-between;
                    align-items:flex-start;
                    gap:16px;
                    flex-wrap:wrap;
                ">

                        <div>

                            <div
                                style="
                            font-size:1.25rem;
                            font-weight:650;
                            color:#0F172A;
                        ">
                                {{ $course->code }}
                                <span
                                    style="
                                font-weight:400;
                                color:#475569;
                            ">
                                    — {{ $course->name }}
                                </span>
                            </div>

                            <div
                                style="
                            margin-top:5px;
                            font-size:.85rem;
                            color:#64748B;
                        ">
                                Faculty teaching this course
                            </div>

                        </div>

                        <a href="{{ route('admin.feedback-sessions.courses', [
                            'semester' => $semester,
                            'department' => $department,
                        ]) }}"
                            class="btn-secondary btn-sm">
                            ← Courses
                        </a>

                    </div>

                </div>


                {{-- =========================================================
             Status Summary
        ========================================================== --}}
                <div
                    style="
                display:grid;
                grid-template-columns:repeat(3, minmax(0, 1fr));
                gap:12px;
                margin-bottom:24px;
            ">

                    {{-- Remaining --}}
                    <div
                        style="
                    border:1px solid #E2E8F0;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#FFFFFF;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Remaining
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#94A3B8;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#0F172A;
                    ">
                            {{ $remainingCount }}
                        </div>

                    </div>


                    {{-- Ongoing --}}
                    <div
                        style="
                    border:1px solid #BFDBFE;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#F8FBFF;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Ongoing
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#3B82F6;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#1D4ED8;
                    ">
                            {{ $ongoingCount }}
                        </div>

                    </div>


                    {{-- Done --}}
                    <div
                        style="
                    border:1px solid #BBF7D0;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#F8FFF9;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Done
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#22C55E;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#15803D;
                    ">
                            {{ $doneCount }}
                        </div>

                    </div>

                </div>


                {{-- =========================================================
             Faculty List
        ========================================================== --}}
                <div>

                    <div
                        style="
                    margin-bottom:10px;
                    font-size:.9rem;
                    font-weight:600;
                    color:#0F172A;
                ">
                        Faculty
                    </div>


                    <div
                        style="
                    display:flex;
                    flex-direction:column;
                    gap:10px;
                ">

                        @forelse($facultyList as $item)
                            @php
                                $session = $item->feedbackSession;
                            @endphp

                            <div
                                style="
                            border:1px solid #E2E8F0;
                            border-radius:11px;
                            padding:16px 18px;
                            background:#FFFFFF;
                        ">

                                <div
                                    style="
                                display:flex;
                                align-items:center;
                                justify-content:space-between;
                                gap:18px;
                                flex-wrap:wrap;
                            ">

                                    {{-- Faculty Information --}}
                                    <div
                                        style="
                                    min-width:220px;
                                ">

                                        <div
                                            style="
                                        font-size:.98rem;
                                        font-weight:600;
                                        color:#0F172A;
                                    ">
                                            {{ $item->faculty->name }}
                                        </div>

                                        <div
                                            style="
                                        margin-top:4px;
                                        display:flex;
                                        align-items:center;
                                        gap:8px;
                                        flex-wrap:wrap;
                                        font-size:.78rem;
                                        color:#64748B;
                                    ">

                                            <span>
                                                {{ $item->faculty->employee_id ?? $item->faculty->email }}
                                            </span>

                                            <span>•</span>

                                            <span>
                                                Section {{ $item->section->section_name ?? $item->section->name }}
                                            </span>

                                        </div>

                                    </div>


                                    {{-- Status + Action --}}
                                    <div
                                        style="
                                    display:flex;
                                    align-items:center;
                                    gap:10px;
                                    flex-wrap:wrap;
                                ">

                                        @if ($item->feedback_status === 'not_taken')
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#F1F5F9;
                                            color:#475569;
                                        ">
                                                Remaining
                                            </span>

                                            <a href="{{ route('admin.feedback-sessions.open', [
                                                'section' => $item->section->id,
                                                'faculty' => $item->faculty->id,
                                            ]) }}"
                                                class="btn-primary btn-sm">
                                                Open Feedback
                                            </a>
                                        @elseif($item->feedback_status === 'ongoing')
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#EFF6FF;
                                            color:#1D4ED8;
                                        ">
                                                Ongoing
                                            </span>

                                            @if ($session)
                                                <a href="{{ route('admin.feedback-sessions.responses', $session) }}"
                                                    class="btn-secondary btn-sm">
                                                    View Responses
                                                </a>
                                            @endif
                                        @elseif($item->feedback_status === 'completed')
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#ECFDF5;
                                            color:#15803D;
                                        ">
                                                Done
                                            </span>

                                            @if ($session)
                                                <a href="{{ route('admin.feedback-sessions.responses', $session) }}"
                                                    class="btn-secondary btn-sm">
                                                    View Results
                                                </a>
                                            @endif
                                        @elseif($item->feedback_status === 'awaiting_release')
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#FFFBEB;
                                            color:#A16207;
                                        ">
                                                Awaiting Release
                                            </span>

                                            @if ($session)
                                                <a href="{{ route('admin.feedback-sessions.responses', $session) }}"
                                                    class="btn-secondary btn-sm">
                                                    View Responses
                                                </a>
                                            @endif
                                        @elseif($item->feedback_status === 'scheduled')
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#F5F3FF;
                                            color:#6D28D9;
                                        ">
                                                Scheduled
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div
                                style="
                            border:1px solid #E2E8F0;
                            border-radius:11px;
                            padding:36px 20px;
                            text-align:center;
                            background:#FFFFFF;
                            color:#64748B;
                            font-size:.9rem;
                        ">
                                No faculty members are assigned to this course.
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>
        @endif


    </div>

@endsection
