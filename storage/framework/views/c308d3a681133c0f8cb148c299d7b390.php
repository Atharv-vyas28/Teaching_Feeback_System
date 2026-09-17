<?php $__env->startSection('title', 'Staff Assignments'); ?>
<?php $header = 'Staff Assignments'; $subheader = 'Assign Staff to a course section and, when needed, a specific feedback session.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?> <div class="alert-success" style="margin-bottom:16px;"><?php echo e(session('success')); ?></div> <?php endif; ?>
<?php if(session('error')): ?> <div class="alert-error" style="margin-bottom:16px;"><?php echo e(session('error')); ?></div> <?php endif; ?>

<div style="display:grid;grid-template-columns:minmax(290px,1fr) 2fr;gap:20px;">
    <div class="card" style="align-self:start;">
        <div class="card-header"><h3>Create Assignment</h3></div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.staff-assignments.store')); ?>">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:14px;"><label class="form-label">Staff *</label><select class="form-select" name="staff_id" required><option value="">Select staff</option><?php $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($staff->id); ?>"><?php echo e($staff->name); ?> (<?php echo e($staff->employee_id ?? 'No ID'); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div style="margin-bottom:14px;"><label class="form-label">Course Section *</label><select class="form-select" name="class_section_id" required><option value="">Select course section</option><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($section->id); ?>"><?php echo e($section->course->code); ?> — <?php echo e($section->course->name); ?> / <?php echo e($section->course->department?->code); ?> / Sec <?php echo e($section->section_name); ?> / <?php echo e($section->semester?->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div style="margin-bottom:14px;"><label class="form-label">Feedback Release Time</label><input class="form-input" type="datetime-local" name="release_at"></div>
                <div style="margin-bottom:18px;"><label class="form-label">Feedback Deadline / Timeout</label><input class="form-input" type="datetime-local" name="deadline_at"><small style="display:block;margin-top:5px;color:#64748B;">Students cannot submit after this time.</small></div>
                <button class="btn-primary" type="submit">Save Assignment</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Assignment History</h3></div>
        <div style="overflow-x:auto;"><table class="data-table"><thead><tr><th>Staff</th><th>Course / Section</th><th>Feedback Session</th><th>Assigned</th><th>Status</th><th>Action</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><strong><?php echo e($assignment->staff->name); ?></strong><br><small><?php echo e($assignment->staff->employee_id); ?></small></td><td><?php echo e($assignment->section->course->code); ?> — <?php echo e($assignment->section->course->name); ?><br><small><?php echo e($assignment->section->course->department?->name); ?> · Sec <?php echo e($assignment->section->section_name); ?></small></td><td><?php echo e($assignment->feedbackSession ? '#'.$assignment->feedbackSession->id : 'Course access only'); ?></td><td><?php echo e($assignment->assigned_at?->format('d M Y, h:i A') ?? $assignment->created_at->format('d M Y')); ?><br><small>by <?php echo e($assignment->assignedBy?->name ?? 'Admin'); ?></small></td><td><span class="badge <?php echo e($assignment->is_active ? 'badge-green' : 'badge-gray'); ?>"><?php echo e(ucfirst($assignment->status)); ?></span></td><td><?php if($assignment->is_active): ?><form method="POST" action="<?php echo e(route('admin.staff-assignments.deactivate', $assignment)); ?>" onsubmit="return confirm('Deactivate this assignment? Staff access will end immediately.')"><?php echo csrf_field(); ?><button class="btn-secondary btn-sm" type="submit">Deactivate</button></form><?php else: ?> — <?php endif; ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="6" style="text-align:center;padding:35px;color:#94A3B8;">No Staff assignments have been created.</td></tr>
        <?php endif; ?>
        </tbody></table></div>
        <div style="padding:16px;"><?php echo e($assignments->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/admin/staff-assignments/index.blade.php ENDPATH**/ ?>