<?php $__env->startSection('title', 'New Class Session'); ?>
<?php $header = 'Create Class Session'; $subheader = 'Start a new class session and take attendance.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<a href="<?php echo e(route('staff.attendance.create')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:600px;">
    <div class="card-header"><h3>Session Details</h3></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('staff.attendance.store-session')); ?>">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom:16px;">
                <label class="form-label">Class Section <span style="color:#DC2626;">*</span></label>
                <select name="class_section_id" class="form-select" required>
                    <option value="">— Select section —</option>
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($section->id); ?>" <?php echo e(old('class_section_id') == $section->id ? 'selected' : ''); ?>>
                        <?php echo e($section->course->name); ?> — Section <?php echo e($section->section_name); ?> (<?php echo e($section->semester->name ?? 'N/A'); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label">Session Date <span style="color:#DC2626;">*</span></label>
                <input type="date" name="session_date" class="form-input" value="<?php echo e(old('session_date', date('Y-m-d'))); ?>" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Start Time <span style="color:#DC2626;">*</span></label>
                    <input type="time" name="start_time" class="form-input" value="<?php echo e(old('start_time')); ?>" required>
                </div>
                <div>
                    <label class="form-label">End Time <span style="color:#DC2626;">*</span></label>
                    <input type="time" name="end_time" class="form-input" value="<?php echo e(old('end_time')); ?>" required>
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <label class="form-label">Topic / Chapter (optional)</label>
                <input type="text" name="topic" class="form-input" value="<?php echo e(old('topic')); ?>" placeholder="e.g. Introduction to Binary Trees">
            </div>
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn-primary">Create & Take Attendance →</button>
                <a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/staff/attendance/create-session.blade.php ENDPATH**/ ?>