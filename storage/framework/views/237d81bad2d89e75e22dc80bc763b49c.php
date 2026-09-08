<?php $__env->startSection('title', 'Student Dashboard'); ?>
<?php $header = 'Student Dashboard'; $subheader = 'Track your attendance, enrolled courses, and submit feedback.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Courses</div>
<a href="<?php echo e(route('student.attendance')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Attendance
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('student.feedback.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Submit Feedback
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#eeeaff,#ddd6fe);color:#6c55e8;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div class="stat-label">Enrolled Courses</div>
        <div class="stat-value"><?php echo e(count($enrollments)); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:<?php echo e($attendancePct >= 75 ? 'linear-gradient(135deg,#e3faf2,#c3f4de)' : 'linear-gradient(135deg,#ffe4e4,#fecaca)'); ?>;color:<?php echo e($attendancePct >= 75 ? '#10976b' : '#b91c1c'); ?>;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Attendance</div>
        <div class="stat-value"><?php echo e($attendancePct); ?>%</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#fff3dc,#ffe3a0);color:#dd8509;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <div class="stat-label">Pending Feedback</div>
        <div class="stat-value"><?php echo e(count($availableFeedback)); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#e6efff,#c7daff);color:#3576f6;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <div class="stat-label">Submitted</div>
        <div class="stat-value"><?php echo e($completedFeedback); ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    
    <div class="card">
        <div class="card-header"><h3>Enrolled Courses</h3></div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
            <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:#f6f5ff;border-radius:10px;border:1px solid #e8e4ff;">
                <div>
                    <div style="font-weight:700;color:#17213a;font-size:0.87rem;"><?php echo e($enrollment->section->course->name ?? 'N/A'); ?></div>
                    <div style="font-size:0.75rem;color:#75809a;"><?php echo e($enrollment->section->course->code ?? ''); ?> · Sec <?php echo e($enrollment->section->section_name ?? ''); ?></div>
                </div>
                <span class="badge badge-purple"><?php echo e($enrollment->status); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:20px;color:#75809a;">No courses enrolled.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>Pending Feedback</h3>
            <a href="<?php echo e(route('student.feedback.index')); ?>" style="font-size:0.78rem;color:#6c55e8;text-decoration:none;font-weight:700;">View all →</a>
        </div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
            <?php $__empty_1 = true; $__currentLoopData = $availableFeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:#e3faf2;border-radius:10px;border:1px solid #b4ecd5;">
                <div>
                    <div style="font-weight:700;color:#17213a;font-size:0.87rem;"><?php echo e($fb->classSession->section->course->name ?? 'N/A'); ?></div>
                    <div style="font-size:0.75rem;color:#75809a;"><?php echo e($fb->classSession->session_date->format('M d, Y') ?? ''); ?></div>
                </div>
                <a href="<?php echo e(route('student.feedback.show', $fb)); ?>" class="btn-primary btn-sm">Submit →</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:20px;color:#75809a;">No pending feedback.</div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php if(count($todaySessions) > 0): ?>
<div class="card">
    <div class="card-header"><h3>Today's Classes</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead><tr><th>Course</th><th>Time</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $todaySessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600;"><?php echo e($session->section->course->name ?? 'N/A'); ?></td>
                    <td><?php echo e(substr($session->start_time, 0, -3)); ?> – <?php echo e(substr($session->end_time, 0, -3)); ?></td>
                    <td><span class="badge <?php echo e(['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow'][$session->status] ?? 'badge-gray'); ?>"><?php echo e(ucfirst($session->status)); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driver = window.driver.js.driver;
    const tour = driver({
        showProgress: true,
        animate: true,
        steps: [
            { element: '.sidebar-brand', popover: { title: 'Welcome to SmartPulse!', description: 'This is your student dashboard.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("student.attendance")); ?>"]', popover: { title: 'Track Attendance', description: 'View your attendance history for all enrolled courses.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("student.feedback.index")); ?>"]', popover: { title: 'Submit Feedback', description: 'When a faculty member opens a feedback session, it will appear here.', side: 'right', align: 'start' } },
            { element: '.card:nth-of-type(2)', popover: { title: 'Pending Feedback', description: 'Quickly access any feedback forms that require your attention right from your dashboard.', side: 'top', align: 'start' } },
        ]
    });

    const startBtn = document.getElementById('start-tour-btn');
    if (startBtn) {
        startBtn.addEventListener('click', () => tour.drive());
    }

    if (!localStorage.getItem('tourCompleted_student')) {
        setTimeout(() => tour.drive(), 500);
        localStorage.setItem('tourCompleted_student', 'true');
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/student/dashboard.blade.php ENDPATH**/ ?>