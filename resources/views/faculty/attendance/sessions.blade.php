@extends('layouts.dashboard')

@section('title', 'My Sessions')

@php
    $header = 'Class Sessions';
    $subheader = 'All class sessions you have conducted.';
@endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>

<a href="{{ route('faculty.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2 7-7 7 7 2 2M5 10v10h14V10M9 20v-6h6v6"/>
    </svg>
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="{{ route('faculty.attendance.sessions') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 00-2-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
    </svg>
    Courses
</a>

<a href="{{ route('faculty.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4v16m8-8H4"/>
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

<a href="{{ route('faculty.feedback.my-ratings') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
    </svg>
    My Ratings
</a>
@endsection

@section('page-actions')
<a href="{{ route('faculty.attendance.create') }}" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4v16m8-8H4"/>
    </svg>
    New Session
</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:24px;">
    <div style="padding:20px 20px 8px;">
        <div style="font-size:1rem;font-weight:700;color:#0F172A;">
            My Courses
        </div>
        <div style="font-size:0.82rem;color:#64748B;margin-top:4px;">
            Select a course to view its lectures and session-level attendance.
        </div>
    </div>

    <div style="padding:12px 20px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px;">
        @forelse($courseSummaries as $section)
            @php
                $attendancePercentage = $section->total_marked > 0
                    ? round(($section->present_count / $section->total_marked) * 100)
                    : 0;

                $isSelected = $selectedSectionId === $section->id;
            @endphp

            <a
                href="{{ route('faculty.attendance.sessions', ['section_id' => $section->id]) }}"
                style="
                    display:block;
                    text-decoration:none;
                    border:1px solid {{ $isSelected ? '#3B82F6' : '#E2E8F0' }};
                    background:{{ $isSelected ? '#EFF6FF' : '#FFFFFF' }};
                    border-radius:10px;
                    padding:16px;
                    transition:0.2s ease;
                "
            >
                <div style="display:flex;justify-content:space-between;gap:12px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;color:#3B82F6;text-transform:uppercase;">
                            {{ $section->course?->code ?? 'Course' }}
                        </div>

                        <div style="font-weight:700;color:#0F172A;margin-top:4px;">
                            {{ $section->course?->name ?? 'N/A' }}
                        </div>

                        <div style="font-size:0.78rem;color:#64748B;margin-top:3px;">
                            Section {{ $section->section_name }}
                        </div>
                    </div>

                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px;">
                    <div style="background:#F8FAFC;border-radius:7px;padding:10px;">
                        <div style="font-size:0.7rem;color:#64748B;">Lectures taken</div>
                        <div style="font-size:1.05rem;font-weight:700;color:#0F172A;margin-top:3px;">
                            {{ $section->total_sessions }}
                        </div>
                    </div>

                    <div style="background:#F8FAFC;border-radius:7px;padding:10px;">
                        <div style="font-size:0.7rem;color:#64748B;">Overall attendance</div>
                        <div style="font-size:1.05rem;font-weight:700;color:#0F172A;margin-top:3px;">
                            {{ $section->total_marked > 0 ? $attendancePercentage . '%' : '—' }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="color:#94A3B8;padding:12px 0;">
                No courses have been assigned to you yet.
            </div>
        @endforelse
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <div>
            <h3>
                {{ $selectedSectionId
                    ? 'Attendance Trend for Selected Course'
                    : 'Overall Attendance Trend' }}
            </h3>

            <div style="font-size:.8rem;color:#64748B;margin-top:4px;">
                Percentage of students present in each lecture.
            </div>
        </div>
    </div>

    @if($attendanceTrend->isNotEmpty())
        <div style="height:310px;padding:10px 18px 18px;position:relative;">
            <canvas id="courseAttendanceTrendChart"></canvas>
        </div>
    @else
        <div style="padding:35px 20px;text-align:center;color:#94A3B8;">
            No attendance data is available yet.
        </div>
    @endif
</div>

<div class="card">
    <div style="padding:20px 20px 12px;display:flex;justify-content:space-between;align-items:center;gap:12px;">
        <div>
            <div style="font-size:1rem;font-weight:700;color:#0F172A;">
                @if($selectedSectionId)
                    Course Sessions
                @else
                    All Sessions
                @endif
            </div>

            <div style="font-size:0.82rem;color:#64748B;margin-top:4px;">
                @if($selectedSectionId)
                    Showing lectures for the selected course section.
                @else
                    Select a course above to focus on its lectures.
                @endif
            </div>
        </div>

        @if($selectedSectionId)
            <a href="{{ route('faculty.attendance.sessions') }}" class="btn-secondary btn-sm">
                View All
            </a>
        @endif
    </div>

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Topic</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Attendance</th> 
                    <th></th> 
                </tr>
            </thead>

            <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#0F172A;">
                                {{ $session->section?->course?->name ?? 'N/A' }}
                            </div>
                            <div style="font-size:0.75rem;color:#94A3B8;">
                                Section {{ $session->section?->section_name ?? '' }}
                            </div>
                        </td>

                        <td>{{ $session->topic ?? '—' }}</td>
                        <td>{{ $session->session_date?->format('M d, Y') ?? 'N/A' }}</td>

                        <td style="font-size:0.8rem;color:#64748B;">
                            {{ substr($session->start_time, 0, -3) ?? '' }} – {{ substr($session->end_time, 0, -3) ?? '' }}
                        </td>

                        <td>
                            <span class="badge {{
                                [
                                    'completed' => 'badge-green',
                                    'ongoing' => 'badge-blue',
                                    'scheduled' => 'badge-yellow',
                                    'cancelled' => 'badge-red'
                                ][$session->status] ?? 'badge-gray'
                            }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>


                        <td>
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;min-width:215px;">

                            @if($session->total_attendance === 0)
                                <a
                                    href="{{ route('faculty.attendance.take', $session) }}"
                                    class="btn-primary btn-sm"
                                    style="display:inline-flex;align-items:center;gap:6px;"
                                >
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Mark Attendance
                                </a>
                            @endif

                            @if($session->total_attendance > 0)
                                <a
                                    href="{{ route('faculty.attendance.analytics', $session) }}"
                                    class="btn-secondary btn-sm"
                                    title="View attendance analytics"
                                    style="
                                        display:inline-flex;
                                        align-items:center;
                                        justify-content:center;
                                        width:fit;
                                        height:34px;
                                        padding:0;
                                        border-radius:8px;
                                        background-color:rgba(195, 192, 15, 0.178);
                                    "
                                >
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7"/>
                                    </svg>
                                    <span class="sr-only">Analysis</span>
                                </a>
                            @endif

                        </div>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">
                            No sessions found for this course.

                            <a href="{{ route('faculty.attendance.create') }}" style="color:#3B82F6;">
                                Create a session →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sessions->hasPages())
        <div style="padding:16px 20px;">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let trendData = @json($attendanceTrend);
    console.log(trendData);
    
    trendData = trendData.filter(data => data.percentage != -1);
    console.log(trendData);

    const chartElement = document.getElementById(
        'courseAttendanceTrendChart'
    );

    if (!chartElement || trendData.length === 0) {
        return;
    }

    new Chart(chartElement, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.label),
            datasets: [{
                label: 'Attendance Percentage',
                data: trendData.map(item => item.percentage),
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,.12)',
                fill: true,
                borderWidth: 3,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#4F46E5',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return ' Attendance: ' +
                                context.parsed.y.toFixed(1) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        callback: function (value) {
                            return value + '%';
                        }
                    },
                    grid: {
                        color: '#E2E8F0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 35,
                        minRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                }
            }
        }
    });
});
</script>