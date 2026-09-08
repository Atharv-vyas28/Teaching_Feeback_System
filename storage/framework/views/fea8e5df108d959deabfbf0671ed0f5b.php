<?php $__env->startSection('title', 'Attendance History'); ?>
<?php $header = 'Attendance History'; $subheader = 'Detailed student attendance summary across your assigned sections.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link">Dashboard</a>
<div class="nav-section-label">Attendance</div>
<a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="nav-link">My Sessions</a>
<a href="<?php echo e(route('staff.attendance.history')); ?>" class="nav-link active">Attendance History</a>
<a href="<?php echo e(route('staff.attendance.create')); ?>" class="nav-link">New Session</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('staff.feedback.index')); ?>" class="nav-link">Assigned Feedback</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3>Filter History</h3>
    </div>
    <form method="GET" action="<?php echo e(route('staff.attendance.history')); ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">Course Section</label>
            <select name="section_id" class="form-input" style="min-width:200px;">
                <option value="">All My Sections</option>
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sec->id); ?>" <?php echo e($filteredSectionId == $sec->id ? 'selected' : ''); ?>>
                        <?php echo e($sec->course->name); ?> (Sec: <?php echo e($sec->section_name); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">From Date</label>
            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-input">
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">To Date</label>
            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-input">
        </div>
        <div>
            <label style="font-size:0.75rem;color:#64748B;margin-bottom:4px;display:block;">Search Student</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name or Roll No" class="form-input">
        </div>
        <div>
            <button type="submit" class="btn-primary" style="height:38px;">Apply Filters</button>
            <?php if(request()->hasAny(['section_id','date_from','date_to','search'])): ?>
                <a href="<?php echo e(route('staff.attendance.history')); ?>" class="btn-secondary" style="height:38px;display:inline-flex;align-items:center;">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Course</th>
                    <th>Total</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $pct = $row->total_classes > 0 ? round(($row->present_count / $row->total_classes) * 100, 1) : 0;
                    $color = $pct >= 75 ? '#15803D' : ($pct >= 60 ? '#B45309' : '#DC2626');
                ?>
                <tr>
                    <td style="font-weight:600;color:#0F172A;"><?php echo e($row->student_name); ?></td>
                    <td><?php echo e($row->roll_number ?? '—'); ?></td>
                    <td>
                        <div><?php echo e($row->course_name); ?></div>
                        <div style="font-size:0.75rem;color:#94A3B8;"><?php echo e($row->course_code); ?></div>
                    </td>
                    <td><?php echo e($row->total_classes); ?></td>
                    <td style="color:#15803D;font-weight:600;"><?php echo e($row->present_count); ?></td>
                    <td style="color:#DC2626;font-weight:600;"><?php echo e($row->absent_count); ?></td>
                    <td><span class="badge" style="background:#F1F5F9;color:<?php echo e($color); ?>;font-weight:700;"><?php echo e($pct); ?>%</span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No attendance records found for the selected filters.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($summary->hasPages()): ?>
    <div style="padding:16px 20px;"><?php echo e($summary->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/staff/attendance/history.blade.php ENDPATH**/ ?>