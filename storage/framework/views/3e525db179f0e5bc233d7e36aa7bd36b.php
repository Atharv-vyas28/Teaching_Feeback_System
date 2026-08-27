<?php $__env->startSection('title', 'Add Course'); ?>
<?php $header = 'Create Course'; $subheader = 'Add a new course to the system.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Academic</div>
<a href="<?php echo e(route('admin.departments.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Departments
</a>
<a href="<?php echo e(route('admin.courses.index')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Courses
</a>
<a href="<?php echo e(route('admin.semesters.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<a href="<?php echo e(route('admin.enrollments.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    Enrollments
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.courses.store')); ?>">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom:16px;">
                <label class="form-label">Course Name <span style="color:#DC2626;">*</span></label>
                <input type="text" name="name" class="form-input" value="<?php echo e(old('name')); ?>" required>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label">Course Code <span style="color:#DC2626;">*</span></label>
                <input type="text" name="code" class="form-input" value="<?php echo e(old('code')); ?>" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Department <span style="color:#DC2626;">*</span></label>
                    <select name="department_id" class="form-select" required>
                        <option value="">Select...</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Program <span style="color:#DC2626;">*</span></label>
                    <select name="program_id" class="form-select" required>
                        <option value="">Select...</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($prog->id); ?>" <?php echo e(old('program_id') == $prog->id ? 'selected' : ''); ?>><?php echo e($prog->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Credits <span style="color:#DC2626;">*</span></label>
                    <input type="number" name="credits" class="form-input" min="1" max="10" value="<?php echo e(old('credits')); ?>" required>
                </div>
                <div>
                    <label class="form-label">Semester Number <span style="color:#DC2626;">*</span></label>
                    <input type="number" name="semester_number" class="form-input" min="1" max="12" value="<?php echo e(old('semester_number')); ?>" required>
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="3"><?php echo e(old('description')); ?></textarea>
            </div>
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn-primary">Create Course</button>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/courses/create.blade.php ENDPATH**/ ?>