@extends('layouts.dashboard')

@section('title', 'Take Attendance')

@php
    $header = 'Take Attendance';
    $subheader = $classSession->section->course->name ?? 'Class Session';
    $lectureNumber = $classSession->lecture_number ?? $classSession->id;
@endphp

@section('sidebar-nav') <div class="nav-section-label">Main</div>


    <a href="{{ route('faculty.dashboard') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>

    <div class="nav-section-label">Attendance</div>

    <a href="{{ route('faculty.attendance.sessions') }}" class="nav-link active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 00-2-2M9 5a2 2 0 012-2h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
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

    <a href="{{ route('faculty.feedback.index') }}" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        Feedback Sessions
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

    <style>
        .attendance-hero {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .attendance-hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .lecture-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 11px;
            margin-bottom: 10px;
            border: 1px solid #dbeafe;
            border-radius: 20px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .attendance-hero h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .attendance-hero p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.85rem;
        }

        .session-label {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
        }

        .session-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
        }

        .session-detail-card {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .session-detail-label {
            margin-bottom: 6px;
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .session-detail-value {
            color: #334155;
            font-size: 0.85rem;
            font-weight: 650;
        }

        .attendance-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.03);
        }

        .summary-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .summary-total .summary-icon {
            background: #eff6ff;
            color: #2563eb;
        }

        .summary-present .summary-icon {
            background: #ecfdf5;
            color: #16a34a;
        }

        .summary-absent .summary-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .summary-label {
            margin-bottom: 3px;
            color: #64748b;
            font-size: 0.72rem;
        }

        .summary-number {
            color: #0f172a;
            font-size: 1.3rem;
            font-weight: 750;
        }

        .attendance-table {
            width: 100%;
        }

        .attendance-table th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .attendance-table tbody tr {
            transition: background 0.2s ease;
        }

        .attendance-table tbody tr:hover {
            background: #f8fafc;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 750;
            flex-shrink: 0;
        }

        .attendance-toggle-btn {
            min-width: 105px;
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.78rem;
            font-weight: 650;
            transition: all 0.2s ease;
        }

        .attendance-toggle-btn:hover {
            transform: translateY(-1px);
        }

        .attendance-toggle-btn.present {
            background: #dcfce7;
            color: #15803d;
        }

        .attendance-toggle-btn.present:hover {
            background: #bbf7d0;
        }

        .attendance-toggle-btn.absent {
            background: #fee2e2;
            color: #dc2626;
        }

        .attendance-toggle-btn.absent:hover {
            background: #fecaca;
        }

        .attendance-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .attendance-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .student-search {
            position: relative;
            flex: 1 1 250px;
            max-width: 330px;
        }

        .student-search svg {
            position: absolute;
            top: 50%;
            left: 12px;
            width: 16px;
            height: 16px;
            color: #94A3B8;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .student-search input {
            width: 100%;
            box-sizing: border-box;
            padding: 9px 12px 9px 38px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            outline: none;
            color: #0F172A;
            font-size: .8rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .student-search input:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
        }

        .attendance-bulk-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        @media (max-width: 600px) {
            .student-search {
                max-width: none;
                flex-basis: 100%;
            }

            .attendance-bulk-actions {
                width: 100%;
            }

            .attendance-bulk-actions button {
                flex: 1;
            }
        }

        @media (max-width: 700px) {
            .attendance-summary {
                grid-template-columns: 1fr;
            }

            .attendance-hero {
                padding: 18px;
            }

            .session-details {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .session-details {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons>* {
                flex: 1;
                text-align: center;
            }
        }
    </style>

    <div class="attendance-hero">
        <div class="attendance-hero-top">
            <div>
                <div class="lecture-badge">
                     Lecture {{ $lectureNumber }}
                </div>


                <h2>
                    {{ $classSession->section->course->name ?? 'Class Session' }}
                </h2>

                <p>
                    {{ $classSession->topic ?? 'No topic specified for this lecture.' }}
                </p>
            </div>

            <div class="session-label">
                Attendance Session
            </div>
        </div>

        <div class="session-details">
            <div class="session-detail-card">
                <div class="session-detail-label">Lecture</div>
                <div class="session-detail-value">
                    Lecture {{ $lectureNumber }}
                </div>
            </div>

            <div class="session-detail-card">
                <div class="session-detail-label">Date</div>
                <div class="session-detail-value">
                    {{ $classSession->session_date->format('M d, Y') }}
                </div>
            </div>

            <div class="session-detail-card">
                <div class="session-detail-label">Time</div>
                <div class="session-detail-value">
                    {{ substr($classSession->start_time, 0, -3) }} – {{ substr($classSession->end_time, 0, -3) }}
                </div>
            </div>

            <div class="session-detail-card">
                <div class="session-detail-label">Topic</div>
                <div class="session-detail-value">
                    {{ $classSession->topic ?? 'N/A' }}
                </div>
            </div>
        </div>


    </div>

    <div class="attendance-summary">
        <div class="summary-card summary-total">
            <div class="summary-icon">👥</div>
            <div>
                <div class="summary-label">Total Students</div>
                <div class="summary-number">{{ count($students) }}</div>
            </div>
        </div>


        <div class="summary-card summary-present">
            <div class="summary-icon">✓</div>
            <div>
                <div class="summary-label">Present</div>
                <div class="summary-number" id="presentCount">0</div>
            </div>
        </div>

        <div class="summary-card summary-absent">
            <div class="summary-icon">✕</div>
            <div>
                <div class="summary-label">Absent</div>
                <div class="summary-number" id="absentCount">0</div>
            </div>
        </div>


    </div>

    <div class="card">
        <div class="card-header"
            style="display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;">
            <div>
                <h3 style="margin-bottom:4px;">Mark Attendance</h3>
                <div style="font-size:0.78rem;color:#64748B;">
                    Click a student's attendance button to switch between Present and Absent.
                </div>
            </div>


            <div class="attendance-toolbar" style="display:flex">
                <div class="student-search">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>

                    <input type="search" id="studentSearch" placeholder="Search name or roll number..." autocomplete="off"
                        oninput="filterStudents(this.value)">
                </div>

                <div class="attendance-bulk-actions">
                    <button type="button" onclick="markAll('present')" class="btn-success btn-sm">
                        ✓ Mark All Present
                    </button>

                    <button type="button" onclick="markAll('absent')" class="btn-secondary btn-sm">
                        ✕ Mark All Absent
                    </button>
                </div>
            </div>
        </div>


        <form method="POST" action="{{ route('faculty.attendance.save', $classSession) }}">
            @csrf

            <div style="overflow-x:auto;">
                <table class="data-table attendance-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Roll Number</th>
                            <th>Attendance</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($students as $i => $student)
                            @php
                                $existing = $existingAttendance[$student->id] ?? null;
                                $currentStatus = $existing?->status ?? 'present';
                            @endphp

                            <tr class="student-row"
                                data-search="{{ strtolower($student->name . ' ' . ($student->roll_number ?? '')) }}">
                                <td style="color:#94A3B8;font-weight:650;">
                                    {{ $i + 1 }}
                                </td>

                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <div style="font-weight:650;color:#0F172A;">
                                                {{ $student->name }}
                                            </div>

                                            <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;">
                                                Student
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td style="color:#64748B;font-size:0.82rem;">
                                    {{ $student->roll_number ?? '—' }}
                                </td>

                                <td>
                                    <input type="hidden" name="attendance[{{ $student->id }}][status]"
                                        value="{{ $currentStatus }}" class="status-input"
                                        id="status-{{ $student->id }}">

                                    <button type="button" id="attendance-btn-{{ $student->id }}"
                                        class="attendance-toggle-btn {{ $currentStatus === 'present' ? 'present' : 'absent' }}"
                                        onclick="toggleAttendance({{ $student->id }})">
                                        {{ $currentStatus === 'present' ? '✓ Present' : '✕ Absent' }}
                                    </button>
                                </td>

                                <td>
                                    <input type="text" name="attendance[{{ $student->id }}][remarks]"
                                        class="form-input" style="width:170px;" value="{{ $existing?->remarks ?? '' }}"
                                        placeholder="Optional remark">
                                </td>
                            </tr>
                        @endforeach
                        <tr id="studentSearchEmptyRow" hidden>
                            <td colspan="5" style="padding:32px;text-align:center;color:#94A3B8;">
                                No student matches your search.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="attendance-actions">
                <div style="font-size:0.78rem;color:#64748B;">
                    Review the attendance before saving.
                </div>

                <div class="action-buttons">
                    <a href="{{ route('faculty.attendance.sessions') }}" class="btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn-primary">
                        Save Attendance
                    </button>
                </div>
            </div>
        </form>


    </div>

    @push('scripts')
        <script>
            function filterStudents(query) {
                const searchTerm = query.trim().toLowerCase();
                const rows = document.querySelectorAll('.student-row');
                const emptyRow = document.getElementById('studentSearchEmptyRow');

                let visibleRows = 0;

                rows.forEach(function(row) {
                    const studentDetails = row.dataset.search || '';
                    const matches = studentDetails.includes(searchTerm);

                    row.hidden = !matches;

                    if (matches) {
                        visibleRows++;
                    }
                });

                if (emptyRow) {
                    emptyRow.hidden = visibleRows > 0;
                }
            }

            function updateButton(studentId, status) {
                const input = document.getElementById(`status-${studentId}`);
                const button = document.getElementById(`attendance-btn-${studentId}`);

                if (!input || !button) return;

                input.value = status;
                button.classList.remove('present', 'absent');
                button.classList.add(status);

                button.textContent =
                    status === 'present' ?
                    '✓ Present' :
                    '✕ Absent';
            }

            function toggleAttendance(studentId) {
                const input = document.getElementById(`status-${studentId}`);

                if (!input) return;

                const newStatus =
                    input.value === 'present' ?
                    'absent' :
                    'present';

                updateButton(studentId, newStatus);
                updateCounts();
            }

            function markAll(status) {
                document.querySelectorAll('.status-input').forEach(input => {
                    const studentId = input.id.replace('status-', '');
                    updateButton(studentId, status);
                });

                updateCounts();
            }

            function updateCounts() {
                let present = 0;
                let absent = 0;

                document.querySelectorAll('.status-input').forEach(input => {
                    if (input.value === 'present') {
                        present++;
                    } else if (input.value === 'absent') {
                        absent++;
                    }
                });

                const presentCount = document.getElementById('presentCount');
                const absentCount = document.getElementById('absentCount');

                if (presentCount) presentCount.textContent = present;
                if (absentCount) absentCount.textContent = absent;
            }

            document.addEventListener('DOMContentLoaded', updateCounts);
        </script>
    @endpush

@endsection
