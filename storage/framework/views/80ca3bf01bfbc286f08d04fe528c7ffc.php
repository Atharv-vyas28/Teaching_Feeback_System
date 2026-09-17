<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php
    $header = 'Admin Dashboard';
    $subheader = 'Institution-wide academic performance and feedback intelligence.';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    .analytics-dashboard {
        --primary: #6c55e8;
        --primary-dark: #4f3cc9;
        --blue: #3576f6;
        --green: #16a772;
        --orange: #f59e0b;
        --red: #ef5b69;
        --ink: #17213a;
        --muted: #75809a;
        --line: #e9ecf4;
        --surface: #ffffff;
    }

    .analytics-dashboard .filter-card,
    .analytics-dashboard .panel,
    .analytics-dashboard .kpi-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(45, 55, 90, .05);
    }

    .analytics-dashboard .filter-card {
        padding: 16px;
        margin-bottom: 20px;
    }

    .analytics-dashboard .filters {
        display: grid;
        grid-template-columns: repeat(6, minmax(130px, 1fr));
        gap: 12px;
        align-items: end;
    }

    .analytics-dashboard label {
        display: block;
        color: var(--muted);
        font-size: .72rem;
        font-weight: 700;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .analytics-dashboard select,
    .analytics-dashboard input {
        width: 100%;
        border: 1px solid #dfe4ee;
        border-radius: 9px;
        background: #fbfcff;
        color: var(--ink);
        padding: 9px 10px;
        outline: none;
    }

    .analytics-dashboard .filter-actions {
        display: flex;
        gap: 8px;
    }

    .analytics-dashboard .apply-btn,
    .analytics-dashboard .reset-btn {
        padding: 10px 13px;
        border-radius: 9px;
        font-size: .82rem;
        font-weight: 700;
        text-decoration: none;
        border: 0;
        cursor: pointer;
        white-space: nowrap;
    }

    .analytics-dashboard .apply-btn {
        background: var(--primary);
        color: white;
    }

    .analytics-dashboard .reset-btn {
        background: #f0effb;
        color: var(--primary-dark);
    }

    .analytics-dashboard .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(170px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .analytics-dashboard .kpi-card {
        padding: 17px;
    }

    .analytics-dashboard .kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .analytics-dashboard .kpi-label {
        color: var(--muted);
        font-size: .75rem;
        font-weight: 700;
    }

    .analytics-dashboard .kpi-icon {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        font-size: 1rem;
    }

    .analytics-dashboard .kpi-value {
        color: var(--ink);
        font-size: 1.65rem;
        font-weight: 800;
        margin-top: 12px;
    }

    .analytics-dashboard .kpi-note {
        color: #94a0b7;
        margin-top: 3px;
        font-size: .72rem;
    }

    .analytics-dashboard .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .analytics-dashboard .two-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .analytics-dashboard .panel {
        padding: 19px;
    }

    .analytics-dashboard .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .analytics-dashboard .panel h3 {
        margin: 0;
        font-size: .98rem;
        color: var(--ink);
    }

    .analytics-dashboard .panel-subtitle {
        color: var(--muted);
        font-size: .75rem;
    }

    .analytics-dashboard .chart-box {
        height: 270px;
        position: relative;
    }

    .analytics-dashboard .table-wrap {
        overflow-x: auto;
    }

    .analytics-dashboard table {
        width: 100%;
        border-collapse: collapse;
    }

    .analytics-dashboard th {
        color: #93a0b9;
        font-size: .68rem;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 11px 9px;
        white-space: nowrap;
    }

    .analytics-dashboard td {
        color: #34415c;
        font-size: .82rem;
        padding: 13px 9px;
        border-top: 1px solid #eff1f6;
    }

    .analytics-dashboard .rank {
        color: var(--primary);
        font-weight: 800;
    }

    .analytics-dashboard .score {
        font-weight: 800;
        color: var(--ink);
    }

    .analytics-dashboard .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
    }

    .analytics-dashboard .excellent {
        color: #087d57;
        background: #ddfaee;
    }

    .analytics-dashboard .good {
        color: #2563d9;
        background: #e5efff;
    }

    .analytics-dashboard .attention {
        color: #ba6700;
        background: #fff3d5;
    }

    .analytics-dashboard .alert-row {
        display: flex;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid #eff1f6;
    }

    .analytics-dashboard .alert-row:last-child {
        border-bottom: 0;
    }

    .analytics-dashboard .alert-icon {
        background: #fff1d4;
        color: #d97706;
        min-width: 29px;
        height: 29px;
        display: grid;
        place-items: center;
        border-radius: 8px;
    }

    .analytics-dashboard .alert-title {
        font-size: .76rem;
        color: #9a5e09;
        font-weight: 800;
    }

    .analytics-dashboard .alert-text {
        font-size: .78rem;
        color: #64718a;
        margin-top: 2px;
    }

    .analytics-dashboard .review-row {
        padding: 12px 0;
        border-bottom: 1px solid #eff1f6;
    }

    .analytics-dashboard .review-row:last-child {
        border-bottom: 0;
    }

    .analytics-dashboard .review-title {
        color: var(--ink);
        font-weight: 700;
        font-size: .82rem;
    }

    .analytics-dashboard .review-meta {
        color: var(--muted);
        font-size: .72rem;
        margin-top: 3px;
    }

    .analytics-dashboard .review-link {
        color: var(--primary);
        text-decoration: none;
        font-size: .74rem;
        font-weight: 800;
    }

    @media (max-width: 1180px) {
        .analytics-dashboard .filters {
            grid-template-columns: repeat(3, 1fr);
        }

        .analytics-dashboard .kpi-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 850px) {
        .analytics-dashboard .chart-grid,
        .analytics-dashboard .two-grid {
            grid-template-columns: 1fr;
        }

        .analytics-dashboard .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .analytics-dashboard .filters,
        .analytics-dashboard .kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="analytics-dashboard">

    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="filter-card">
        <div class="filters">
            <div>
                <label>Department</label>
                <select name="department_id">
                    <option value="">All departments</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($department->id); ?>"
                            <?php if($filters['department_id'] == $department->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($department->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label>Semester</label>
                <select name="semester_id">
                    <option value="">All semesters</option>
                    <?php $__currentLoopData = \App\Models\Semester::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($semester->id); ?>"
                            <?php if($filters['semester_id'] == $semester->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($semester->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label>Course</label>
                <select name="course_id">
                    <option value="">All courses</option>
                    <?php $__currentLoopData = \App\Models\Course::where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($course->id); ?>"
                            <?php if($filters['course_id'] == $course->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($course->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label>Faculty</label>
                <select name="faculty_id">
                    <option value="">All faculty</option>
                    <?php $__currentLoopData = \App\Models\User::where('role', 'faculty')->where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($faculty->id); ?>"
                            <?php if($filters['faculty_id'] == $faculty->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($faculty->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label>From date</label>
                <input type="date" name="from_date" value="<?php echo e($filters['from_date']); ?>">
            </div>

            <div>
                <label>To date</label>
                <input type="date" name="to_date" value="<?php echo e($filters['to_date']); ?>">
            </div>

            <div class="filter-actions">
                <button type="submit" class="apply-btn">Apply Filters</button>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="reset-btn">Reset</a>
            </div>
        </div>
    </form>

    <div class="kpi-grid">
        <?php
            $kpis = [
                ['Departments', $stats['total_depts'], '◈', '#eeeaff', '#6248e9', 'Institute structure'],
                ['Courses', $stats['total_courses'], '▤', '#e6efff', '#3576f6', 'Active academic courses'],
                ['Faculty', $stats['total_faculty'], '♙', '#e3faf2', '#10976b', 'Active teaching faculty'],
                ['Students', $stats['total_students'], '◉', '#fff0df', '#e37b22', 'Active student records'],
                ['Active Feedback', $stats['active_feedback'], '◌', '#eaf0ff', '#3f69dc', 'Currently collecting responses'],
                ['Pending Review', $stats['pending_review'], '!', '#fff3dc', '#dd8509', 'Admin release required'],
                ['Institute Rating', number_format($stats['average_rating'], 2) . ' / 5', '★', '#f5edff', '#8955d9', 'Released anonymous feedback'],
                ['Average Attendance', number_format($stats['average_attendance'], 1) . '%', '◷', '#e4faf3', '#16a772', 'Present attendance records'],
            ];
        ?>

        <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon, $bg, $color, $note]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label"><?php echo e($label); ?></span>
                    <span class="kpi-icon" style="background:<?php echo e($bg); ?>;color:<?php echo e($color); ?>;">
                        <?php echo e($icon); ?>

                    </span>
                </div>
                <div class="kpi-value"><?php echo e($value); ?></div>
                <div class="kpi-note"><?php echo e($note); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="chart-grid">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Institute Rating Trend</h3>
                    <div class="panel-subtitle">Released feedback performance by semester</div>
                </div>
                <span class="badge good">Rating / 5</span>
            </div>
            <div class="chart-box">
                <canvas id="ratingTrendChart"></canvas>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Feedback Participation</h3>
                    <div class="panel-subtitle">Eligible students versus responses</div>
                </div>
            </div>
            <div class="chart-box">
                <canvas id="responseRateChart"></canvas>
            </div>
        </div>
    </div>

    <div class="two-grid">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Department Performance</h3>
                    <div class="panel-subtitle">Average rating comparison</div>
                </div>
            </div>
            <div class="chart-box">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Attention Required</h3>
                    <div class="panel-subtitle">Automatically detected academic concerns</div>
                </div>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $attentionAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="alert-row">
                    <div class="alert-icon">!</div>
                    <div>
                        <div class="alert-title"><?php echo e($alert['type']); ?></div>
                        <div class="alert-text"><?php echo e($alert['message']); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align:center;color:#8591a7;padding:48px 8px;">
                    ✓ No performance alerts for the selected filters.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel" style="margin-bottom:20px;">
        <div class="panel-head">
            <div>
                <h3>Department Performance Table</h3>
                <div class="panel-subtitle">Drill down from institute to department-level performance.</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Faculty</th>
                        <th>Courses</th>
                        <th>Students</th>
                        <th>Rating</th>
                        <th>Attendance</th>
                        <th>Response Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $departmentPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = $department['rating'] >= 4.5
                                ? ['Excellent', 'excellent']
                                : ($department['rating'] >= 3.5
                                    ? ['Good', 'good']
                                    : ['Needs Attention', 'attention']);
                        ?>
                        <tr>
                            <td>
                                <span class="rank">#<?php echo e($index + 1); ?></span>
                                &nbsp;
                                <strong><?php echo e($department['name']); ?></strong>
                                <div style="font-size:.7rem;color:#99a3b7;">
                                    <?php echo e($department['code']); ?>

                                </div>
                            </td>
                            <td><?php echo e($department['faculty_count']); ?></td>
                            <td><?php echo e($department['course_count']); ?></td>
                            <td><?php echo e($department['student_count']); ?></td>
                            <td class="score">
                                <?php echo e($department['rating'] > 0 ? number_format($department['rating'], 2) . ' / 5' : '—'); ?>

                            </td>
                            <td><?php echo e(number_format($department['attendance'], 1)); ?>%</td>
                            <td><?php echo e(number_format($department['response_rate'], 1)); ?>%</td>
                            <td><span class="badge <?php echo e($status[1]); ?>"><?php echo e($status[0]); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;padding:26px;color:#8792a8;">
                                No department analytics are available for the selected filters.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="two-grid">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Top Faculty Performance</h3>
                    <div class="panel-subtitle">Based on released anonymous feedback only</div>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Courses</th>
                            <th>Rating</th>
                            <th>Responses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $facultyPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($faculty['name']); ?></strong></td>
                                <td><?php echo e($faculty['department']); ?></td>
                                <td><?php echo e($faculty['course_count']); ?></td>
                                <td class="score">
                                    <?php echo e($faculty['rating'] > 0 ? number_format($faculty['rating'], 2) . ' / 5' : '—'); ?>

                                </td>
                                <td><?php echo e($faculty['response_count']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;padding:25px;color:#8792a8;">
                                    No faculty performance data is available.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3>Feedback Awaiting Review</h3>
                    <div class="panel-subtitle">Closed sessions that are not released to faculty</div>
                </div>
                <span class="badge attention"><?php echo e($stats['pending_review']); ?> Pending</span>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $pendingFeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="review-row">
                    <div style="display:flex;justify-content:space-between;gap:12px;">
                        <div>
                            <div class="review-title">
                                <?php echo e($feedback->classSession?->section?->course?->name ?? 'Course'); ?>

                            </div>
                            <div class="review-meta">
                                <?php echo e($feedback->classSession?->topic ?? 'Feedback session'); ?>

                                · <?php echo e($feedback->responses_count); ?> response(s)
                            </div>
                        </div>

                        <a
                            class="review-link"
                            href="<?php echo e(route('admin.feedback-sessions.responses', $feedback)); ?>"
                        >
                            Review
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align:center;color:#8591a7;padding:48px 8px;">
                    No feedback is waiting for review.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3>Recent Class Sessions</h3>
                <div class="panel-subtitle">Latest institute academic activity</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Topic</th>
                        <th>Conducted By</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($session->section?->course?->name ?? 'Course'); ?></strong>
                            </td>
                            <td><?php echo e($session->topic ?? 'No topic'); ?></td>
                            <td><?php echo e($session->conductor?->name ?? 'Unknown'); ?></td>
                            <td><?php echo e($session->session_date?->format('M d, Y') ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge good">
                                    <?php echo e(ucfirst($session->status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;padding:25px;color:#8792a8;">
                                No class sessions match the selected filters.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.Chart) {
        return;
    }

    const trendData = <?php echo json_encode($ratingTrend, 15, 512) ?>;
    const departmentData = <?php echo json_encode($departmentPerformance, 15, 512) ?>;

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    boxWidth: 10,
                    color: '#69758e',
                    font: { size: 11 }
                }
            }
        }
    };

    new Chart(document.getElementById('ratingTrendChart'), {
        type: 'line',
        data: {
            labels: trendData.map(item => item.label),
            datasets: [{
                label: 'Institute Rating',
                data: trendData.map(item => item.rating),
                borderColor: '#6c55e8',
                backgroundColor: 'rgba(108,85,232,.12)',
                borderWidth: 3,
                fill: true,
                tension: .35,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#6c55e8',
                pointBorderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    grid: { color: '#eef0f6' },
                    ticks: { stepSize: 1, color: '#8b96ab' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#8b96ab' }
                }
            }
        }
    });

    new Chart(document.getElementById('responseRateChart'), {
        type: 'doughnut',
        data: {
            labels: ['Submitted', 'Pending / No response'],
            datasets: [{
                data: [
                    <?php echo e((int) $stats['total_responses']); ?>,
                    <?php echo e(max(0, (int) $stats['eligible_students'] - (int) $stats['total_responses'])); ?>

                ],
                backgroundColor: ['#6c55e8', '#e9e8f6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            ...chartDefaults,
            cutout: '72%',
            plugins: {
                ...chartDefaults.plugins,
                title: {
                    display: true,
                    text: '<?php echo e(number_format($stats['response_rate'], 1)); ?>%',
                    position: 'bottom',
                    color: '#17213a',
                    font: { size: 19, weight: '700' }
                }
            }
        }
    });

    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: departmentData.map(item => item.name),
            datasets: [{
                label: 'Average Rating / 5',
                data: departmentData.map(item => item.rating),
                backgroundColor: [
                    '#6c55e8', '#7d69f0', '#5b91f5', '#57b5d8',
                    '#9b77dc', '#7aa1f2', '#51a987', '#e58a56'
                ],
                borderRadius: 7,
                borderSkipped: false
            }]
        },
        options: {
            ...chartDefaults,
            plugins: {
                ...chartDefaults.plugins,
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    grid: { color: '#eef0f6' },
                    ticks: { stepSize: 1, color: '#8b96ab' }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#8b96ab',
                        maxRotation: 30,
                        minRotation: 0
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    setInterval(function () {
        window.location.reload();
    }, 30000);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>