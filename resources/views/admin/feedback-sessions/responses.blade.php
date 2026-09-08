@extends('layouts.dashboard')

@section('title', 'Feedback Review')

@php
    $header = 'Feedback Review';
    $subheader = 'Anonymous feedback analysis for this class session.';
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <h3>
                {{ $session->classSession?->section?->course?->name ?? 'Course' }}
                — {{ $session->classSession?->topic ?? 'Feedback Session' }}
            </h3>

            <p style="margin:5px 0 0;color:#64748B;font-size:.82rem;">
                Submitted responses are anonymous. Student name, roll number,
                email, and student ID are never displayed.
            </p>
        </div>
    </div>

    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;">
            <div class="stat-card">
                <div class="stat-label">Anonymous Responses</div>
                <div class="stat-value">{{ $responses->count() }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Session Status</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    {{ ucfirst($session->status) }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Release Status</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    {{ $session->isReleased() ? 'Released' : 'Under Review' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">

    <div class="card">
        <div class="card-header">
            <h3>Response Submission Timeline</h3>
        </div>

        <div class="card-body" style="height:300px;position:relative;">
            <canvas id="responseTimelineChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Rating Distribution</h3>
        </div>

        <div class="card-body" style="height:300px;position:relative;">
            <canvas id="ratingDistributionChart"></canvas>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h3>Question-wise Feedback Analysis</h3>
    </div>

    <div class="card-body" style="height:360px;position:relative;">
        <canvas id="questionRatingChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Anonymous Individual Responses</h3>
    </div>

    <div class="card-body">
        @forelse($responses as $response)
            <div class="stat-card" style="margin-bottom:18px;">
                <div style="font-size:.8rem;color:#64748B;margin-bottom:10px;">
                    Anonymous Response #{{ $loop->iteration }}
                    · Submitted:
                    {{ $response->submitted_at?->format('M d, Y H:i') ?? 'N/A' }}
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Anonymous Answer</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($response->answers as $answer)
                            <tr>
                                <td>
                                    {{ $answer->question?->question_text ?? 'Question unavailable' }}
                                </td>

                                <td>
                                    @if($answer->question?->type === 'rating')
                                        <strong>
                                            {{ $answer->rating_value ?? '—' }} / 5
                                        </strong>
                                    @else
                                        {{ $answer->text_answer ?? '—' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div style="text-align:center;padding:38px;color:#94A3B8;">
                No feedback has been submitted for this session yet.
            </div>
        @endforelse

        @if($responses->isNotEmpty())
            @if($session->isReleased())
                <div style="background:#DCFCE7;color:#166534;padding:12px 16px;border-radius:8px;">
                    Feedback has been released to the assigned faculty.
                </div>
            @else
                <form
                    method="POST"
                    action="{{ route('admin.feedback-sessions.release', $session) }}"
                    style="margin-top:20px;"
                    onsubmit="return confirm('Release all anonymous feedback analysis and ratings to the assigned faculty?');"
                >
                    @csrf

                    <button type="submit" class="btn-primary">
                        Release Anonymous Feedback to Faculty
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.Chart) {
        return;
    }

    const timeline = @json($responseTimeline);
    const questionAnalysis = @json($questionAnalysis);
    const distribution = @json($ratingDistribution);

    new Chart(document.getElementById('responseTimelineChart'), {
        type: 'line',
        data: {
            labels: timeline.map(item => item.label),
            datasets: [{
                label: 'Responses Submitted',
                data: timeline.map(item => item.count),
                borderColor: '#6C55E8',
                backgroundColor: 'rgba(108,85,232,.12)',
                borderWidth: 3,
                fill: true,
                tension: .35,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#6C55E8',
                pointBorderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('ratingDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                data: [
                    distribution[1],
                    distribution[2],
                    distribution[3],
                    distribution[4],
                    distribution[5]
                ],
                backgroundColor: [
                    '#EF5B69',
                    '#F59E0B',
                    '#FACC15',
                    '#60A5FA',
                    '#16A772'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('questionRatingChart'), {
        type: 'bar',
        data: {
            labels: questionAnalysis.map(item => item.question),
            datasets: [{
                label: 'Average Rating / 5',
                data: questionAnalysis.map(item => item.average),
                backgroundColor: '#3576F6',
                borderRadius: 7,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Average Rating / 5'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 20,
                        minRotation: 0
                    }
                }
            }
        }
    });
});
</script>

<style>
@media (max-width: 900px) {
    #responseTimelineChart,
    #ratingDistributionChart,
    #questionRatingChart {
        max-height: 300px;
    }
}
</style>
@endsection