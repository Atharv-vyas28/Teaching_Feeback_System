<?php $__env->startSection('title', 'Feedback Analytics'); ?>
<?php $header = 'Feedback Analytics'; $subheader = 'Anonymous aggregated feedback for this session.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('faculty.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="<?php echo e(route('faculty.attendance.sessions')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 00-2-2M9 5a2 2 0 012-2h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Courses
</a>

<a href="<?php echo e(route('faculty.attendance.create')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add Lecture
</a>

<div class="nav-section-label">Feedback</div>

<a href="<?php echo e(route('faculty.feedback.index')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
    Feedback Sessions
</a>

<a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
    </svg>
    My Ratings
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $overallRating = (float) ($ratingResult->overall_weighted_rating ?? 0);

    $performancePercentage = $overallRating > 0
        ? round(($overallRating / 5) * 100, 2)
        : 0;

    $responseRate = $eligibleCount > 0
        ? round(($responseCount / $eligibleCount) * 100, 2)
        : 0;

    $pendingResponses = max(0, $eligibleCount - $responseCount);

    /*
     * `weight` is used as maximum marks for each question.
     * Marks earned = (average rating / 5) × maximum marks.
     */
    $ratingRows = collect($questionStats)
        ->filter(fn ($stat) => ($stat['type'] ?? '') === 'rating')
        ->map(function ($stat) {
            $average = (float) ($stat['average'] ?? 0);
            $maximumMarks = (float) ($stat['weight'] ?? 0);
            $marksEarned = $maximumMarks > 0
                ? round(($average / 5) * $maximumMarks, 2)
                : 0;

            return [
                'question' => $stat['question'] ?? 'Question',
                'label' => \Illuminate\Support\Str::limit(
                    $stat['question'] ?? 'Question',
                    28
                ),
                'average' => $average,
                'maximum_marks' => $maximumMarks,
                'marks_earned' => $marksEarned,
                'response_count' => count($stat['values'] ?? []),
            ];
        })
        ->values();

    $totalMaximumMarks = $ratingRows->sum('maximum_marks');
    $totalMarksEarned = $ratingRows->sum('marks_earned');
?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">

    <div class="stat-card">
        <div class="stat-label">Eligible Students</div>
        <div class="stat-value"><?php echo e($eligibleCount); ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Submitted Feedback</div>
        <div class="stat-value"><?php echo e($responseCount); ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Response Rate</div>
        <div class="stat-value"><?php echo e(number_format($responseRate, 1)); ?>%</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Overall Rating</div>
        <div class="stat-value">
            <?php echo e($overallRating > 0 ? number_format($overallRating, 2) . ' / 5' : '—'); ?>

        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teacher Performance</div>
        <div class="stat-value"><?php echo e(number_format($performancePercentage, 1)); ?>%</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Marks Earned</div>
        <div class="stat-value">
            <?php echo e(number_format($totalMarksEarned, 2)); ?>

            <span style="font-size:0.8rem;color:#64748B;">
                / <?php echo e(number_format($totalMaximumMarks, 2)); ?>

            </span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px;margin-bottom:24px;">

    <div class="card">
        <div class="card-header">
            <h3>Feedback Participation</h3>
        </div>

        <div class="card-body" style="height:300px;position:relative;">
            <canvas id="participationChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Teacher Performance</h3>
        </div>

        <div class="card-body" style="text-align:center;padding-top:35px;">
            <div style="font-size:3.2rem;font-weight:800;color:#2563EB;">
                <?php echo e(number_format($performancePercentage, 1)); ?>%
            </div>

            <p style="color:#64748B;font-size:0.85rem;">
                Calculated from question marks and anonymous student ratings.
            </p>

            <div style="height:12px;background:#E2E8F0;border-radius:999px;overflow:hidden;margin-top:20px;">
                <div
                    style="
                        height:100%;
                        width:<?php echo e(min(100, max(0, $performancePercentage))); ?>%;
                        background:linear-gradient(90deg,#2563EB,#60A5FA);
                        border-radius:999px;
                    "
                ></div>
            </div>

            <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#64748B;margin-top:8px;">
                <span>0%</span>
                <span>Target: 80%</span>
                <span>100%</span>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <div>
            <h3>Department Performance Leaderboard</h3>
            <p style="margin:4px 0 0;color:#64748B;font-size:.78rem;">
                Average of each faculty member’s released ratings in this semester.
            </p>
        </div>
        <span class="badge badge-blue">Live released results</span>
    </div>

    <div class="card-body" style="display:grid;grid-template-columns:minmax(0,1.15fr) minmax(300px,.85fr);gap:22px;align-items:center;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Department</th>
                        <th>Faculty Included</th>
                        <th>Average Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $departmentLeaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span style="font-weight:800;color:<?php echo e($department['rank'] <= 3 ? '#2563EB' : '#64748B'); ?>;">
                                    #<?php echo e($department['rank']); ?>

                                </span>
                            </td>
                            <td>
                                <strong><?php echo e($department['name']); ?></strong>
                                <div style="font-size:.72rem;color:#64748B;margin-top:2px;"><?php echo e($department['code']); ?></div>
                            </td>
                            <td><?php echo e($department['faculty_count']); ?></td>
                            <td>
                                <strong style="color:#0F172A;"><?php echo e(number_format($department['average_score'], 2)); ?> / 5</strong>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;padding:28px;color:#94A3B8;">
                                No released department ratings are available for this semester yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="height:250px;position:relative;">
            <canvas id="departmentLeaderboardChart"></canvas>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3>Question-wise Average Rating</h3>
    </div>

    <div class="card-body" style="height:370px;position:relative;">
        <canvas id="ratingChart"></canvas>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3>Marks Earned by Question</h3>
    </div>

    <div class="card-body" style="height:370px;position:relative;">
        <canvas id="marksChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Detailed Performance Analysis</h3>

        <span style="font-size:0.78rem;color:#64748B;">
            Student identity is protected.
        </span>
    </div>

    <div class="card-body">
        <?php $__empty_1 = true; $__currentLoopData = $ratingRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding:16px 0;border-bottom:1px solid #E2E8F0;">
                <div style="font-weight:700;color:#0F172A;margin-bottom:12px;">
                    <?php echo e($row['question']); ?>

                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px;">
                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Average Rating</div>
                        <div style="font-size:1.1rem;font-weight:700;">
                            <?php echo e(number_format($row['average'], 2)); ?> / 5
                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Maximum Marks</div>
                        <div style="font-size:1.1rem;font-weight:700;">
                            <?php echo e(number_format($row['maximum_marks'], 2)); ?>

                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Marks Earned</div>
                        <div style="font-size:1.1rem;font-weight:700;color:#16A34A;">
                            <?php echo e(number_format($row['marks_earned'], 2)); ?>

                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem;color:#64748B;">Responses</div>
                        <div style="font-size:1.1rem;font-weight:700;">
                            <?php echo e($row['response_count']); ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:35px;color:#94A3B8;">
                No released rating feedback is available.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__currentLoopData = $questionStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(($stat['type'] ?? '') === 'text' && !empty($stat['texts'])): ?>
        <div class="card" style="margin-top:24px;">
            <div class="card-header">
                <h3>Comments</h3>
            </div>

            <div class="card-body">
                <div style="font-weight:700;margin-bottom:12px;">
                    <?php echo e($stat['question']); ?>

                </div>

                <?php $__currentLoopData = $stat['texts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background:#F8FAFC;border-left:4px solid #60A5FA;padding:12px 14px;margin-bottom:10px;border-radius:6px;color:#334155;">
                        “<?php echo e($text); ?>”
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ratingRows = <?php echo json_encode($ratingRows, 15, 512) ?>;

    const labels = ratingRows.map(item => item.label);
    const ratings = ratingRows.map(item => item.average);
    const earnedMarks = ratingRows.map(item => item.marks_earned);
    const maximumMarks = ratingRows.map(item => item.maximum_marks);
    const departmentLeaderboard = <?php echo json_encode($departmentLeaderboard, 15, 512) ?>;

    const submittedCount = <?php echo e((int) $responseCount); ?>;
    const pendingCount = <?php echo e((int) $pendingResponses); ?>;

    if (window.Chart) {
        new Chart(document.getElementById('participationChart'), {
            type: 'doughnut',
            data: {
                labels: ['Feedback Submitted', 'No Feedback'],
                datasets: [{
                    data: [submittedCount, pendingCount],
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

        new Chart(document.getElementById('ratingChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Average Rating / 5',
                    data: ratings,
                    backgroundColor: '#2563EB',
                    borderRadius: 6
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
                            text: 'Rating out of 5'
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('marksChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Marks Earned',
                        data: earnedMarks,
                        backgroundColor: '#16A34A',
                        borderRadius: 6
                    },
                    {
                        label: 'Maximum Marks',
                        data: maximumMarks,
                        backgroundColor: '#BFDBFE',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Marks'
                        }
                    }
                }
            }
        });

        const departmentChart = document.getElementById('departmentLeaderboardChart');
        if (departmentChart && departmentLeaderboard.length) {
            new Chart(departmentChart, {
                type: 'bar',
                data: {
                    labels: departmentLeaderboard.map(item => item.code || item.name),
                    datasets: [{
                        label: 'Department Average / 5',
                        data: departmentLeaderboard.map(item => item.average_score),
                        backgroundColor: '#6366F1',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, max: 5, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/faculty/feedback/analytics.blade.php ENDPATH**/ ?>