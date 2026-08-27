<?php $__env->startSection('title', 'Ratings Report'); ?>
<?php $header = 'Faculty Ratings Report'; $subheader = 'Aggregated faculty performance ratings based on student feedback.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">People</div>
<a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Users
</a>
<div class="nav-section-label">Academic</div>
<a href="<?php echo e(route('admin.departments.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Departments
</a>
<a href="<?php echo e(route('admin.courses.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Courses
</a>
<a href="<?php echo e(route('admin.semesters.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('admin.feedback-questions.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Feedback Questions
</a>
<div class="nav-section-label">Reports</div>
<a href="<?php echo e(route('admin.reports.attendance')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    Attendance Report
</a>
<a href="<?php echo e(route('admin.reports.ratings')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    Ratings Report
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.reports.ratings')); ?>" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-select" style="width:220px;">
                    <option value="">All Semesters</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sem->id); ?>" <?php echo e($semesterId == $sem->id ? 'selected' : ''); ?>><?php echo e($sem->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Faculty Performance Rankings</h3>
        <span class="badge badge-blue"><?php echo e(count($ratings)); ?> results</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Faculty Member</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Rating</th>
                    <th>Responses</th>
                    <th>Status</th>
                    <th>Calculated</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:700;color:#94A3B8;"><?php echo e($i + 1); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#1D4ED8;flex-shrink:0;">
                                <?php echo e(strtoupper(substr($r->faculty->name ?? 'F', 0, 1))); ?>

                            </div>
                            <span style="font-weight:600;"><?php echo e($r->faculty->name ?? 'N/A'); ?></span>
                        </div>
                    </td>
                    <td><?php echo e($r->section->course->name ?? 'N/A'); ?></td>
                    <td><?php echo e($r->semester->name ?? 'N/A'); ?></td>
                    <td>
                        <?php if($r->overall_weighted_rating): ?>
                        <span style="font-size:1rem;font-weight:800;color:<?php echo e($r->overall_weighted_rating >= 4 ? '#1D4ED8' : ($r->overall_weighted_rating >= 3 ? '#D97706' : '#DC2626')); ?>;">
                            <?php echo e(number_format($r->overall_weighted_rating, 1)); ?>

                        </span>
                        <span style="color:#94A3B8;font-size:0.75rem;">/ 5</span>
                        <?php else: ?>
                        <span style="color:#94A3B8;">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($r->response_count); ?></td>
                    <td>
                        <?php if($r->overall_weighted_rating >= 4): ?>
                            <span class="badge badge-green">Excellent</span>
                        <?php elseif($r->overall_weighted_rating >= 3): ?>
                            <span class="badge badge-yellow">Stable</span>
                        <?php elseif($r->overall_weighted_rating): ?>
                            <span class="badge badge-red">Needs Improvement</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#94A3B8;font-size:0.8rem;">
                        <?php echo e($r->calculated_at ? \Carbon\Carbon::parse($r->calculated_at)->format('M d, Y') : '—'); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="empty-state" style="text-align:center;padding:40px;color:#94A3B8;">No rating results found for selected filters.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/reports/ratings.blade.php ENDPATH**/ ?>