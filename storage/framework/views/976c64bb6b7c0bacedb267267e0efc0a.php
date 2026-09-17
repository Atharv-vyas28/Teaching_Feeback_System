<?php $__env->startSection('title', 'Manage Users'); ?>
<?php $header = 'Users'; $subheader = 'Manage all system users'; ?>
<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary">+ Add User</a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
    <!-- Filter -->
    <div class="p-4 border-b border-slate-100 flex flex-wrap gap-3">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search name, email, roll..." class="form-input" style="width:220px">
            <select name="role" class="form-select" style="width:140px">
                <option value="">All Roles</option>
                <?php $__currentLoopData = ['admin','faculty','staff','student']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($r); ?>" <?php echo e(request('role')===$r?'selected':''); ?>><?php echo e(ucfirst($r)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn-primary">Filter</button>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">Clear</a>
        </form>
    </div>
    <div class="overflow-auto">
        <table class="data-table">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>ID</th><th>Dept</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-slate-400 text-xs"><?php echo e($user->id); ?></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold"><?php echo e(strtoupper(substr($user->name,0,1))); ?></div>
                            <div>
                                <div class="font-medium"><?php echo e($user->name); ?></div>
                                <div class="text-xs text-slate-400"><?php echo e($user->roll_number ?? $user->employee_id); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-slate-500 text-xs"><?php echo e($user->email); ?></td>
                    <td><span class="badge <?php echo e(['admin'=>'badge-purple','faculty'=>'badge-blue','staff'=>'badge-green','student'=>'badge-yellow'][$user->role] ?? 'badge-gray'); ?>"><?php echo e(ucfirst($user->role)); ?></span></td>
                    <td class="text-xs text-slate-500"><?php echo e($user->roll_number ?? $user->employee_id ?? '—'); ?></td>
                    <td class="text-xs text-slate-500"><?php echo e($user->department->code ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($user->is_active ? 'badge-green' : 'badge-red'); ?>"><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.users.toggle', $user)); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button class="btn-warning btn-sm"><?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?></button>
                            </form>
                            <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn-danger btn-sm" data-confirm="Delete <?php echo e($user->name); ?>?">Delete</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-slate-400 py-8">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/admin/users/index.blade.php ENDPATH**/ ?>