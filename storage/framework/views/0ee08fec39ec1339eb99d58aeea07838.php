<div class="nav-section-label">Main</div>

<a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('staff.dashboard') ? 'active' : ''); ?>">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 7-7 7 7 2 2M5 10v10h14V10M9 20v-6h6v6"/></svg>
    Dashboard
</a>

<div class="nav-section-label">Attendance</div>

<a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="nav-link <?php echo e(request()->routeIs('staff.attendance.sessions') ? 'active' : ''); ?>">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
    My Sessions
</a>

<div class="nav-section-label">Feedback</div>

<a href="<?php echo e(route('staff.feedback.index')); ?>" class="nav-link <?php echo e(request()->routeIs('staff.feedback.*') ? 'active' : ''); ?>">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-5l-5 5v-5z"/></svg>
    Assigned Feedback
</a>

<div class="nav-section-label">Account</div>

<a href="<?php echo e(route('staff.notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('staff.notifications.*') ? 'active' : ''); ?>">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m1 4h4"/></svg>
    Notifications
    <?php if(auth()->user()->unreadNotifications()->count() > 0): ?>
        <span style="margin-left:auto;min-width:18px;padding:1px 5px;border-radius:99px;background:#EF4444;color:#fff;font-size:10px;text-align:center;"><?php echo e(auth()->user()->unreadNotifications()->count()); ?></span>
    <?php endif; ?>
</a>
<?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/staff/partials/sidebar.blade.php ENDPATH**/ ?>