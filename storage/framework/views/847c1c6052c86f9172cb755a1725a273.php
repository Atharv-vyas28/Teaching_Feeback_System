<?php $__env->startSection('title', 'Attendance Analysis'); ?>

<?php
    $header = 'Attendance Analysis';
?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>

<a href="<?php echo e(route('faculty.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2 7-7 7 7 2 2M5 10v10h14V10M9 20v-6h6v6"/>
    </svg>
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="<?php echo e(route('faculty.attendance.sessions')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 00-2-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
    </svg>
    Courses
</a>

<a href="<?php echo e(route('faculty.attendance.create')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4v16m8-8H4"/>
    </svg>
    Add Lecture
</a>

<div class="nav-section-label">Feedback</div>

<a href="<?php echo e(route('faculty.feedback.index')); ?>" class="nav-link">
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
                    <?php echo e($classSession->section?->course?->name ?? 'Course'); ?>

                    — <?php echo e($classSession->topic ?? 'Class Session'); ?>

                </h3>

                <div style="font-size:.8rem;color:#64748B;margin-top:4px;">
                    <?php echo e($classSession->session_date?->format('M d, Y') ?? 'N/A'); ?>

                    · <?php echo e(substr($classSession->start_time, 0, -3) ?? ''); ?>

                    to <?php echo e(substr($classSession->end_time, 0, -3) ?? ''); ?>

                </div>
            </div>

            <a
                href="<?php echo e(route('faculty.attendance.sessions')); ?>"
                class="btn-secondary btn-sm"
            >
                Back to Sessions
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:18px;">

        <div class="stat-card">
            <div class="stat-label">Total Students</div>
            <div class="stat-value"><?php echo e($stats['total']); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Present</div>
            <div class="stat-value" style="color:#16A34A;">
                <?php echo e($stats['present']); ?>

            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Absent</div>
            <div class="stat-value" style="color:#DC2626;">
                <?php echo e($stats['absent']); ?>

            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Attendance</div>
            <div class="stat-value">
                <?php echo e(number_format($stats['attendance_percentage'], 1)); ?>%
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
                    Present Students (<?php echo e($stats['present']); ?>)
                </button>

                <button
                    type="button"
                    class="student-tab"
                    data-target="absent-list"
                >
                    Absent Students (<?php echo e($stats['absent']); ?>)
                </button>

            </div>

            <div id="present-list" class="student-list active">
                <?php echo $__env->make('faculty.attendance.partials.student-list', [
                    'students' => $presentStudents,
                    'title' => 'Present Students'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div id="absent-list" class="student-list">
                <?php echo $__env->make('faculty.attendance.partials.student-list', [
                    'students' => $absentStudents,
                    'title' => 'Absent Students'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const lineData = <?php echo json_encode($lineChartData, 15, 512) ?>;

    new Chart(document.getElementById('attendancePieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [
                    <?php echo e((int) $stats['present']); ?>,
                    <?php echo e((int) $stats['absent']); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/faculty/attendance/analytics.blade.php ENDPATH**/ ?>