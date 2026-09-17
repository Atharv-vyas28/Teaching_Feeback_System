<?php $__env->startSection('title', 'My Sessions'); ?>
<?php $header = 'Class Sessions'; $subheader = 'All sessions for your assigned sections.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<?php echo $__env->make('staff.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Courses
</a>
<a href="<?php echo e(route('staff.attendance.create')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('staff.attendance.create')); ?>" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>Course / Section</th><th>Topic</th><th>Date</th><th>Time</th><th>Attendance</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;"><?php echo e($session->section->course->name ?? 'N/A'); ?></div>
                        <div style="font-size:0.75rem;color:#94A3B8;">Section <?php echo e($session->section->section_name ?? ''); ?></div>
                    </td>
                    <td><?php echo e($session->topic ?? '—'); ?></td>
                    <td><?php echo e($session->session_date->format('M d, Y')); ?></td>
                    <td style="font-size:0.8rem;color:#64748B;"><?php echo e(substr($session->start_time, 0, -3)); ?> – <?php echo e(substr($session->end_time, 0, -3)); ?></td>
                    <td>
                        <span class="badge badge-blue">
                            <?php echo e($session->attendanceRecords->whereIn('status',['present','late'])->count()); ?> / <?php echo e($session->attendanceRecords->count()); ?>

                        </span>
                    </td>
                    <td><span class="badge <?php echo e(['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray'); ?>"><?php echo e(ucfirst($session->status)); ?></span></td>
                    <td>
                        <?php if(in_array($session->status, ['ongoing','scheduled'])): ?>
                        <a href="<?php echo e(route('staff.attendance.take', $session)); ?>" class="btn-primary btn-sm">Take Attendance</a>
                        <?php else: ?>
                        <a href="<?php echo e(route('staff.attendance.take', $session)); ?>" class="btn-secondary btn-sm">View</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No sessions yet. <a href="<?php echo e(route('staff.attendance.create')); ?>" style="color:#3B82F6;">Create one →</a></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($sessions->hasPages()): ?>
    <div style="padding:16px 20px;"><?php echo e($sessions->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/staff/attendance/sessions.blade.php ENDPATH**/ ?>