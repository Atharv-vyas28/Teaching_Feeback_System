
<?php $__env->startSection('title', 'Edit User'); ?>
<?php $header = 'Edit User: '.$user->name; $subheader = 'Update user information'; ?>
<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">← Back</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
<form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="form-input" required>
        </div>
        <div>
            <label class="form-label">New Password (leave blank to keep)</label>
            <input type="password" name="password" class="form-input" minlength="8">
        </div>
        <div>
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-input">
        </div>
        <div>
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
                <?php $__currentLoopData = ['student','faculty','staff','admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($r); ?>" <?php echo e(old('role',$user->role)===$r?'selected':''); ?>><?php echo e(ucfirst($r)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                <option value="">— Select —</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id',$user->department_id)==$dept->id?'selected':''); ?>><?php echo e($dept->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Program</label>
            <select name="program_id" class="form-select">
                <option value="">— Select —</option>
                <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($prog->id); ?>" <?php echo e(old('program_id',$user->program_id)==$prog->id?'selected':''); ?>><?php echo e($prog->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="form-label">Roll Number</label>
            <input type="text" name="roll_number" value="<?php echo e(old('roll_number',$user->roll_number)); ?>" class="form-input">
        </div>
        <div>
            <label class="form-label">Employee ID</label>
            <input type="text" name="employee_id" value="<?php echo e(old('employee_id',$user->employee_id)); ?>" class="form-input">
        </div>
        <div>
            <label class="form-label">Current Semester</label>
            <input type="number" name="current_semester" value="<?php echo e(old('current_semester',$user->current_semester)); ?>" class="form-input" min="1" max="12">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?php echo e(old('phone',$user->phone)); ?>" class="form-input">
        </div>
        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active',$user->is_active)?'checked':''); ?> class="rounded">
            <label for="is_active" class="form-label mb-0">Active</label>
        </div>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-primary">Update User</button>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>