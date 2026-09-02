<?php $__env->startSection('title', 'Semesters'); ?>
<?php $header = 'Semesters'; $subheader = 'Manage academic years and semesters.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Semesters</h3>
    </div>
    <div class="card-body">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:600; color:#0F172A;"><?php echo e($sem->name); ?></td>
                        <td><?php echo e($sem->academicYear->name ?? 'N/A'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($sem->start_date)->format('M d, Y')); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($sem->end_date)->format('M d, Y')); ?></td>
                        <td>
                            <span class="badge <?php echo e($sem->is_current ? 'badge-green' : 'badge-gray'); ?>">
                                <?php echo e($sem->is_current ? 'Current' : 'Past/Future'); ?>

                            </span>
                        </td>
                        <td>
                            <?php if(!$sem->is_current): ?>
                            <form method="POST" action="<?php echo e(route('admin.semesters.set-current', $sem)); ?>" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-primary btn-sm">Set Current</button>
                            </form>
                            <?php else: ?>
                            <span style="font-size:0.8rem;color:#94A3B8;">Active Semester</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">No semesters found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/semesters/index.blade.php ENDPATH**/ ?>