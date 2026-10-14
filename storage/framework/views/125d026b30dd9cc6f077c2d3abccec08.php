<?php $__env->startSection('title', 'Notifications'); ?>
<?php $header = 'Notifications'; $subheader = 'Your course and feedback assignment updates.'; ?>
<?php $__env->startSection('sidebar-nav'); ?>
<?php echo $__env->make('staff.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--
<div class="nav-section-label">Main</div><a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link">Dashboard</a>
<div class="nav-section-label">Feedback</div><a href="<?php echo e(route('staff.feedback.index')); ?>" class="nav-link">Assigned Feedback</a><a href="<?php echo e(route('staff.notifications.index')); ?>" class="nav-link active">Notifications</a>
-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card"><div class="card-header"><h3>All Notifications</h3><form method="POST" action="<?php echo e(route('staff.notifications.read-all')); ?>"><?php echo csrf_field(); ?><button class="btn-secondary btn-sm">Mark all as read</button></form></div>
<div class="card-body"><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(route('staff.notifications.read', $notification->id)); ?>" style="display:block;padding:14px;border-bottom:1px solid #E2E8F0;text-decoration:none;color:#1E293B;background:<?php echo e($notification->read_at ? '#fff' : '#F5F3FF'); ?>"><strong><?php echo e($notification->data['title'] ?? 'Notification'); ?></strong><p style="margin-top:4px;color:#64748B"><?php echo e($notification->data['message'] ?? ''); ?></p><small style="color:#94A3B8"><?php echo e($notification->created_at->format('d M Y, h:i A')); ?></small></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p style="padding:30px;text-align:center;color:#64748B">No notifications yet.</p><?php endif; ?></div><div style="padding:16px"><?php echo e($notifications->links()); ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/staff/notifications/index.blade.php ENDPATH**/ ?>