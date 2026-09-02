<?php $__env->startSection('title', 'Manage Courses'); ?>
<?php $header = 'Courses'; $subheader = 'Manage all courses across departments.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>All Courses</h3>
        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn-primary btn-sm">Add Course</a>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.courses.index')); ?>" style="margin-bottom:20px; display:flex; gap:10px; max-width:400px;">
            <select name="department_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Credits</th>
                        <th>Sem</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:600; color:#0F172A;"><?php echo e($course->code); ?></td>
                        <td><?php echo e($course->name); ?></td>
                        <td><?php echo e($course->department->code ?? 'N/A'); ?></td>
                        <td><?php echo e($course->credits); ?></td>
                        <td><?php echo e($course->semester_number ?? '—'); ?></td>
                        <td>
                            <span class="badge <?php echo e($course->is_active ? 'badge-green' : 'badge-gray'); ?>">
                                <?php echo e($course->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" class="btn-secondary btn-sm">Edit</a>
                                <a href="<?php echo e(route('admin.courses.sections', $course)); ?>" class="btn-primary btn-sm">Sections</a>
                                <form method="POST" action="<?php echo e(route('admin.courses.destroy', $course)); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm" data-confirm="Are you sure you want to delete this course?">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No courses found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($courses->hasPages()): ?>
        <div style="margin-top:16px;"><?php echo e($courses->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>