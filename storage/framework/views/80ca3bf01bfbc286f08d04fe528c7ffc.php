

<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $header = 'Admin Dashboard'; $subheader = 'Institute-wide overview'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section">Main</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section">People</div>
<a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Users
</a>
<div class="nav-section">Academic</div>
<a href="<?php echo e(route('admin.departments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.departments*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Departments
</a>
<a href="<?php echo e(route('admin.courses.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.courses*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Courses
</a>
<a href="<?php echo e(route('admin.semesters.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.semesters*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<a href="<?php echo e(route('admin.enrollments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.enrollments*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    Enrollments
</a>
<div class="nav-section">Feedback</div>
<a href="<?php echo e(route('admin.feedback-questions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.feedback-questions*') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Feedback Questions
</a>
<div class="nav-section">Reports</div>
<a href="<?php echo e(route('admin.reports.attendance')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.reports.attendance') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    Attendance Report
</a>
<a href="<?php echo e(route('admin.reports.ratings')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.reports.ratings') ? 'active' : ''); ?>">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    Ratings Report
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <?php
        $statItems = [
            ['label'=>'Students',  'value'=>$stats['total_students'],  'color'=>'bg-blue-100 text-blue-600',   'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['label'=>'Faculty',   'value'=>$stats['total_faculty'],   'color'=>'bg-purple-100 text-purple-600','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['label'=>'Staff',     'value'=>$stats['total_staff'],     'color'=>'bg-green-100 text-green-600',  'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'],
            ['label'=>'Courses',   'value'=>$stats['total_courses'],   'color'=>'bg-yellow-100 text-yellow-600','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label'=>'Depts',     'value'=>$stats['total_depts'],     'color'=>'bg-red-100 text-red-600',     'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['label'=>'Active FB', 'value'=>$stats['active_sessions'], 'color'=>'bg-orange-100 text-orange-600','icon'=>'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        ?>
        <?php $__currentLoopData = $statItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stat-card">
            <div class="stat-icon <?php echo e($stat['color']); ?> mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($stat['icon']); ?>"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800"><?php echo e($stat['value']); ?></div>
            <div class="text-xs text-slate-500 mt-0.5"><?php echo e($stat['label']); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Sessions -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Recent Class Sessions</h3>
                <a href="<?php echo e(route('admin.reports.attendance')); ?>" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="overflow-auto">
                <table class="data-table">
                    <thead><tr><th>Course</th><th>Conducted By</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="font-medium text-slate-700"><?php echo e($session->section->course->name ?? 'N/A'); ?></div>
                                <div class="text-xs text-slate-400"><?php echo e($session->topic ?? 'No topic'); ?></div>
                            </td>
                            <td><?php echo e($session->conductor->name ?? 'N/A'); ?></td>
                            <td><?php echo e($session->session_date->format('M d, Y')); ?></td>
                            <td>
                                <?php $sc = ['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray'; ?>
                                <span class="badge <?php echo e($sc); ?>"><?php echo e(ucfirst($session->status)); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-slate-400 py-6">No sessions yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Recently Added Users</h3>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="text-xs text-blue-600 hover:underline">Manage users</a>
            </div>
            <div class="overflow-auto">
                <table class="data-table">
                    <thead><tr><th>Name</th><th>Role</th><th>Email</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold"><?php echo e(strtoupper(substr($u->name,0,1))); ?></div> <?php echo e($u->name); ?></div></td>
                            <td><span class="badge <?php echo e(['admin'=>'badge-purple','faculty'=>'badge-blue','staff'=>'badge-green','student'=>'badge-yellow'][$u->role] ?? 'badge-gray'); ?>"><?php echo e(ucfirst($u->role)); ?></span></td>
                            <td class="text-slate-400 text-xs"><?php echo e($u->email); ?></td>
                            <td><span class="badge <?php echo e($u->is_active ? 'badge-green' : 'badge-red'); ?>"><?php echo e($u->is_active ? 'Active' : 'Inactive'); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary">Add User</a>
            <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn-secondary">Add Course</a>
            <a href="<?php echo e(route('admin.departments.index')); ?>" class="btn-secondary">Departments</a>
            <a href="<?php echo e(route('admin.feedback-questions.index')); ?>" class="btn-secondary">Manage Questions</a>
            <a href="<?php echo e(route('admin.reports.ratings')); ?>" class="btn-secondary">View Ratings</a>
        </div>
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
            { element: '.sidebar-brand', popover: { title: 'Welcome to SmartPulse!', description: 'This is your admin dashboard. Let us show you around.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("admin.users.index")); ?>"]', popover: { title: 'Manage Users', description: 'Here you can add or manage students, faculty, and staff.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("admin.courses.index")); ?>"]', popover: { title: 'Academic Setup', description: 'Manage departments, courses, and sections.', side: 'right', align: 'start' } },
            { element: 'a[href="<?php echo e(route("admin.reports.ratings")); ?>"]', popover: { title: 'Reports', description: 'View institute-wide attendance and feedback analytics.', side: 'right', align: 'start' } },
            { element: '.grid.grid-cols-2', popover: { title: 'Quick Stats', description: 'Get a bird\'s eye view of your institute\'s current status.', side: 'bottom', align: 'start' } },
        ]
    });

    const startBtn = document.getElementById('start-tour-btn');
    if (startBtn) {
        startBtn.addEventListener('click', () => tour.drive());
    }

    if (!localStorage.getItem('tourCompleted_admin')) {
        setTimeout(() => tour.drive(), 500);
        localStorage.setItem('tourCompleted_admin', 'true');
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>