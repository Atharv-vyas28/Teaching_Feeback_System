<?php $__env->startSection('title', 'Feedback'); ?>
<?php $header = 'My Feedback'; $subheader = 'Submit feedback for your attended classes.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Courses</div>
<a href="<?php echo e(route('student.attendance')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Attendance
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('student.feedback.index')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Submit Feedback
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(count($available) > 0): ?>
<div style="margin-bottom:24px;">
    <h2 style="font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
        <span class="badge badge-green"><?php echo e(count($available)); ?></span> Available to Submit
    </h2>
    <div style="display:grid;gap:12px;">
        <?php $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:14px;padding:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-weight:700;color:#0F172A;font-size:0.95rem;"><?php echo e($fb->classSession->section->course->name ?? 'N/A'); ?></div>
                <div style="font-size:0.8rem;color:#64748B;margin-top:3px;">
                    <?php echo e($fb->classSession->session_date->format('M d, Y') ?? ''); ?>

                    <?php if($fb->classSession->topic): ?> · <?php echo e($fb->classSession->topic); ?> <?php endif; ?>
                </div>
            </div>
            <a href="<?php echo e(route('student.feedback.show', $fb)); ?>" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Submit Feedback
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<?php if(count($completed) > 0): ?>
<div style="margin-bottom:24px;">
    <h2 style="font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
        <span class="badge badge-blue"><?php echo e(count($completed)); ?></span> Submitted
    </h2>
    <div style="display:grid;gap:12px;">
        <?php $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:14px;padding:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-weight:700;color:#0F172A;font-size:0.95rem;"><?php echo e($fb->classSession->section->course->name ?? 'N/A'); ?></div>
                <div style="font-size:0.8rem;color:#64748B;margin-top:3px;"><?php echo e($fb->classSession->session_date->format('M d, Y') ?? ''); ?></div>
            </div>
            <span class="badge badge-blue">✓ Submitted Anonymously</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<?php if(count($ineligible) > 0): ?>
<div>
    <h2 style="font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
        <span class="badge badge-gray"><?php echo e(count($ineligible)); ?></span> Not Eligible
    </h2>
    <div style="display:grid;gap:12px;">
        <?php $__currentLoopData = $ineligible; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:14px;padding:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-weight:700;color:#64748B;font-size:0.95rem;"><?php echo e($item['session']->classSession->section->course->name ?? 'N/A'); ?></div>
                <div style="font-size:0.8rem;color:#94A3B8;margin-top:3px;"><?php echo e($item['reason']); ?></div>
            </div>
            <span class="badge badge-gray">Ineligible</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<?php if(count($available) === 0 && count($completed) === 0 && count($ineligible) === 0): ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px;color:#94A3B8;">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 16px;opacity:0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        <p>No feedback sessions available right now.</p>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/student/feedback/index.blade.php ENDPATH**/ ?>