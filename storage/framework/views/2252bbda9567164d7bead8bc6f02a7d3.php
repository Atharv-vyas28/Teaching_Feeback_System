<?php $__env->startSection('title', 'Faculty Dashboard'); ?>
<?php $header = 'Faculty Dashboard'; $subheader = 'Manage attendance, feedback sessions, and view your performance ratings.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('faculty.dashboard')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="<?php echo e(route('faculty.attendance.sessions')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Courses
</a>
<a href="<?php echo e(route('faculty.attendance.create')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('faculty.feedback.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Feedback Sessions
</a>
<a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    My Ratings
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    <?php
    $statItems = [
        ['label'=>'My Sections',     'value'=>$stats['sections'],        'color'=>'background:linear-gradient(135deg,#eeeaff,#ddd6fe);color:#6c55e8', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label'=>'Total Sessions',  'value'=>$stats['total_sessions'],  'color'=>'background:linear-gradient(135deg,#e6efff,#c7daff);color:#3576f6', 'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>'Active Feedback', 'value'=>$stats['active_feedback'], 'color'=>'background:linear-gradient(135deg,#e3faf2,#c3f4de);color:#10976b', 'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['label'=>'Avg Rating',      'value'=>$stats['overall_rating'] ? number_format($stats['overall_rating'],1).'/5' : 'N/A', 'color'=>'background:linear-gradient(135deg,#fff3dc,#ffe3a0);color:#dd8509', 'icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
    ];
    ?>
    <?php $__currentLoopData = $statItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="stat-card">
        <div class="stat-icon" style="<?php echo e($s['color']); ?>">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($s['icon']); ?>"/></svg>
        </div>
        <div class="stat-label"><?php echo e($s['label']); ?></div>
        <div class="stat-value"><?php echo e($s['value']); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    
    <div class="card">
        <div class="card-header">
            <h3>Recent Class Sessions</h3>
            <a href="<?php echo e(route('faculty.attendance.sessions')); ?>" style="font-size:0.78rem;color:#6c55e8;text-decoration:none;font-weight:700;">View all →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Course</th><th>Date</th><th>Status</th><th>Feedback</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#0F172A;"><?php echo e($session->section->course->name ?? 'N/A'); ?></div>
                            <div style="font-size:0.75rem;color:#94A3B8;"><?php echo e($session->topic ?? 'No topic'); ?></div>
                        </td>
                        <td><?php echo e($session->session_date->format('M d, Y')); ?></td>
                        <td><span class="badge <?php echo e(['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray'); ?>"><?php echo e(ucfirst($session->status)); ?></span></td>
                        <td>
                            <?php if($session->feedbackSession): ?>
                                <span class="badge badge-<?php echo e(['draft'=>'gray','active'=>'green','closed'=>'blue'][$session->feedbackSession->status] ?? 'gray'); ?>"><?php echo e(ucfirst($session->feedbackSession->status)); ?></span>
                            <?php else: ?>
                                <span style="color:#94A3B8;font-size:0.78rem;">None</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" style="text-align:center;padding:30px;color:#94A3B8;">No sessions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>Recent Feedback Ratings</h3>
            <a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" style="font-size:0.78rem;color:#6c55e8;text-decoration:none;font-weight:700;">View all →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>Course</th><th>Rating</th><th>Responses</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ratingResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:600;"><?php echo e($r->section->course->name ?? 'N/A'); ?></td>
                        <td>
                            <span style="font-weight:800;font-size:1rem;color:<?php echo e(($r->overall_weighted_rating ?? 0) >= 4 ? '#6c55e8' : '#dd8509'); ?>;">
                                <?php echo e($r->overall_weighted_rating ? number_format($r->overall_weighted_rating,1) : '—'); ?>

                            </span>
                            <?php if($r->overall_weighted_rating): ?><span style="color:#75809a;font-size:0.75rem;">/5</span><?php endif; ?>
                        </td>
                        <td><?php echo e($r->response_count); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" style="text-align:center;padding:30px;color:#94A3B8;">No ratings yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card" style="margin-top:20px;">
    <div class="card-header"><h3>Quick Actions</h3></div>
    <div class="card-body" style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="<?php echo e(route('faculty.attendance.create')); ?>" class="btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Lecture
        </a>
        <a href="<?php echo e(route('faculty.attendance.sessions')); ?>" class="btn-secondary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Manage Lectures
        </a>
        <a href="<?php echo e(route('faculty.feedback.index')); ?>" class="btn-secondary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            Feedback Sessions
        </a>
        <a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" class="btn-secondary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            View Ratings
        </a>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driver = window.driver.js.driver;
    const tour = driver({
        showProgress: true,
        animate: true,
        steps: [
            { element: '.sidebar-brand', popover: { title: 'Welcome to SmartPulse!', description: 'This is your faculty dashboard.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("faculty.attendance.create")); ?>"]', popover: { title: 'New Class Session', description: 'Start a new session here to mark student attendance.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("faculty.feedback.index")); ?>"]', popover: { title: 'Feedback Sessions', description: 'After class, open feedback forms here to allow students to rate the session.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("faculty.feedback.my-ratings")); ?>"]', popover: { title: 'My Ratings', description: 'Check your aggregated feedback scores and analytics.', side: 'right', align: 'start' } },
            { element: '.card', popover: { title: 'Quick Overview', description: 'Your recent sessions and feedback at a glance.', side: 'bottom', align: 'start' } },
        ]
    });

    const startBtn = document.getElementById('start-tour-btn');
    if (startBtn) {
        startBtn.addEventListener('click', () => tour.drive());
    }

    if (!localStorage.getItem('tourCompleted_faculty')) {
        setTimeout(() => tour.drive(), 500);
        localStorage.setItem('tourCompleted_faculty', 'true');
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/faculty/dashboard.blade.php ENDPATH**/ ?>