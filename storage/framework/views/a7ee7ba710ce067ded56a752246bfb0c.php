<?php $__env->startSection('title', 'Attendance Report'); ?>
<?php $header = 'Attendance Report'; $subheader = 'Full attendance records across all sessions and students.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.reports.attendance')); ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label class="form-label">Filter by Semester</label>
                <select name="semester_id" class="form-select" style="width:200px;">
                    <option value="">All Semesters</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sem->id); ?>" <?php echo e(request('semester_id') == $sem->id ? 'selected' : ''); ?>><?php echo e($sem->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Filter by Student</label>
                <select name="student_id" class="form-select" style="width:220px;">
                    <option value="">All Students</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st->id); ?>" <?php echo e(request('student_id') == $st->id ? 'selected' : ''); ?>><?php echo e($st->name); ?> (<?php echo e($st->roll_number); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Apply Filters</button>
            <?php if(request('semester_id') || request('student_id')): ?>
            <a href="<?php echo e(route('admin.reports.attendance')); ?>" class="btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Attendance Records</h3>
        <span class="badge badge-blue"><?php echo e($attendance->total()); ?> records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Session Date</th>
                    <th>Status</th>
                    <th>Feedback Enabled</th>
                    <th>Marked At</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;"><?php echo e($rec->student->name ?? 'N/A'); ?></div>
                        <div style="font-size:0.75rem;color:#94A3B8;"><?php echo e($rec->student->roll_number ?? ''); ?></div>
                    </td>
                    <td><?php echo e($rec->session->section->course->name ?? 'N/A'); ?></td>
                    <td><?php echo e($rec->session->session_date->format('M d, Y') ?? 'N/A'); ?></td>
                    <td>
                        <span class="badge <?php echo e(['present'=>'badge-green','absent'=>'badge-red','late'=>'badge-yellow','excused'=>'badge-blue'][$rec->status] ?? 'badge-gray'); ?>">
                            <?php echo e(ucfirst($rec->status)); ?>

                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo e($rec->feedback_enabled ? 'badge-green' : 'badge-gray'); ?>">
                            <?php echo e($rec->feedback_enabled ? 'Yes' : 'No'); ?>

                        </span>
                    </td>
                    <td style="color:#94A3B8;font-size:0.8rem;"><?php echo e($rec->marked_at ? \Carbon\Carbon::parse($rec->marked_at)->format('M d, H:i') : '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="empty-state">No attendance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($attendance->hasPages()): ?>
    <div style="padding:16px 20px;"><?php echo e($attendance->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/reports/attendance.blade.php ENDPATH**/ ?>