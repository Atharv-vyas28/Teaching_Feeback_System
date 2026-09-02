@extends('layouts.dashboard')

@section('title', 'Attendance Analysis')

@php
    $header = 'Attendance Analysis';
    $subheader = 'Attendance and feedback eligibility for this class session.';
@endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>

<a href="{{ route('faculty.dashboard') }}" class="nav-link">
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link active">
    My Sessions
</a>

<a href="{{ route('faculty.attendance.create') }}" class="nav-link">
    New Session
</a>

<div class="nav-section-label">Feedback</div>

<a href="{{ route('faculty.feedback.index') }}" class="nav-link">
    Feedback Sessions
</a>

<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">
    My Ratings
</a>
@endsection

@section('content')
<style>
    .attendance-analysis .chart-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    .attendance-analysis .chart-box {
        height: 240px;
        position: relative;
    }

    .attendance-analysis .student-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .attendance-analysis .student-tab {
        border: 0;
        border-radius: 8px;
        padding: 9px 13px;
        cursor: pointer;
        background: #F1F5F9;
        color: #64748B;
        font-weight: 700;
    }

    .attendance-analysis .student-tab.active {
        background: #4F46E5;
        color: #fff;
    }

    .attendance-analysis .student-list {
        display: none;
    }

    .attendance-analysis .student-list.active {
        display: block;
    }

    @media (max-width: 850px) {
        .attendance-analysis .chart-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="attendance-analysis">

    <div class="card" style="margin-bottom:18px;">
        <div class="card-header">
            <div>
                <h3>
                    {{ $classSession->section?->course?->name ?? 'Course' }}
                    — {{ $classSession->topic ?? 'Class Session' }}
                </h3>

                <div style="font-size:.8rem;color:#64748B;margin-top:4px;">
                    {{ $classSession->session_date?->format('M d, Y') ?? 'N/A' }}
                    · {{ $classSession->start_time ?? '' }}
                    to {{ $classSession->end_time ?? '' }}
                </div>
            </div>

            <a
                href="{{ route('faculty.attendance.sessions') }}"
                class="btn-secondary btn-sm"
            >
                Back to Sessions
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:18px;">

        <div class="stat-card">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Present</div>
            <div class="stat-value" style="color:#16A34A;">
                {{ $stats['present'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Absent</div>
            <div class="stat-value" style="color:#DC2626;">
                {{ $stats['absent'] }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-value">
                {{ number_format($stats['attendance_percentage'], 1) }}%
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Feedback Enabled</div>
            <div class="stat-value" style="color:#2563EB;">
                {{ $stats['feedback_enabled'] }}
            </div>
        </div>
    </div>

    <div class="chart-grid">

        <div class="card">
            <div class="card-header">
                <h3>Attendance Distribution</h3>
            </div>

            <div class="card-body chart-box">
                <canvas id="attendancePieChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Feedback Eligibility</h3>
            </div>

            <div class="card-body chart-box">
                <canvas id="feedbackPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:18px;">
        <div class="card-header">
            <div>
                <h3>Attendance Snapshot by Student</h3>
                <span style="font-size:.75rem;color:#64748B;">
                    1 = Present / Late, 0 = Absent
                </span>
            </div>
        </div>

        <div class="card-body" style="height:280px;position:relative;">
            <canvas id="attendanceLineChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Student Attendance Lists</h3>
        </div>

        <div class="card-body">
            <div class="student-tabs">
                <button
                    type="button"
                    class="student-tab active"
                    data-target="present-list"
                >
                    Present Students ({{ $stats['present'] }})
                </button>

                <button
                    type="button"
                    class="student-tab"
                    data-target="absent-list"
                >
                    Absent Students ({{ $stats['absent'] }})
                </button>

                <button
                    type="button"
                    class="student-tab"
                    data-target="feedback-list"
                >
                    Feedback Enabled ({{ $stats['feedback_enabled'] }})
                </button>
            </div>

            <div id="present-list" class="student-list active">
                @include('faculty.attendance.partials.student-list', [
                    'students' => $presentStudents,
                    'title' => 'Present Students'
                ])
            </div>

            <div id="absent-list" class="student-list">
                @include('faculty.attendance.partials.student-list', [
                    'students' => $absentStudents,
                    'title' => 'Absent Students'
                ])
            </div>

            <div id="feedback-list" class="student-list">
                @include('faculty.attendance.partials.student-list', [
                    'students' => $feedbackEnabledStudents,
                    'title' => 'Students Eligible for Feedback'
                ])
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const lineData = @json($lineChartData);

    new Chart(document.getElementById('attendancePieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [
                    {{ (int) $stats['present'] }},
                    {{ (int) $stats['absent'] }}
                ],
                backgroundColor: ['#16A34A', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('feedbackPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Feedback Enabled', 'Feedback Not Enabled'],
            datasets: [{
                data: [
                    {{ (int) $stats['feedback_enabled'] }},
                    {{ (int) $stats['feedback_disabled'] }}
                ],
                backgroundColor: ['#2563EB', '#E2E8F0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('attendanceLineChart'), {
        type: 'line',
        data: {
            labels: lineData.map(item => item.student),
            datasets: [{
                label: 'Attendance',
                data: lineData.map(item => item.value),
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,.10)',
                fill: true,
                borderWidth: 3,
                tension: 0.25,
                pointRadius: 4,
                pointBackgroundColor: '#4F46E5'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 1,
                    ticks: {
                        stepSize: 1,
                        callback: function (value) {
                            return value === 1 ? 'Present' : 'Absent';
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 35,
                        minRotation: 0
                    }
                }
            }
        }
    });

    document.querySelectorAll('.student-tab').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.student-tab').forEach(function (item) {
                item.classList.remove('active');
            });

            document.querySelectorAll('.student-list').forEach(function (item) {
                item.classList.remove('active');
            });

            button.classList.add('active');

            document
                .getElementById(button.dataset.target)
                .classList
                .add('active');
        });
    });
});
</script>
@endsection