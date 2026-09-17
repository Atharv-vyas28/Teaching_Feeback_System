<?php $__env->startSection('title', 'Departments'); ?>
<?php $header = 'Departments & Programs'; $subheader = 'Manage academic departments'; ?>
<?php $__env->startSection('sidebar-nav'); ?> <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">All Departments</h3>
        </div>
        <div class="overflow-auto">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Code</th><th>Programs</th><th>Members</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><div class="font-medium"><?php echo e($dept->name); ?></div></td>
                        <td><span class="badge badge-blue"><?php echo e($dept->code); ?></span></td>
                        <td><?php echo e($dept->programs_count); ?></td>
                        <td><?php echo e($dept->users_count); ?></td>
                        <td><span class="badge <?php echo e($dept->is_active ? 'badge-green' : 'badge-red'); ?>"><?php echo e($dept->is_active ? 'Active' : 'Inactive'); ?></span></td>
                        <td>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('admin.departments.programs', $dept)); ?>" class="btn-secondary btn-sm">Programs</a>
                                <button onclick="openEditDept(<?php echo e($dept->id); ?>, '<?php echo e(addslashes($dept->name)); ?>', '<?php echo e($dept->code); ?>', '<?php echo e(addslashes($dept->description)); ?>', <?php echo e($dept->is_active ? 1:0); ?>)" class="btn-secondary btn-sm">Edit</button>
                                <form method="POST" action="<?php echo e(route('admin.departments.destroy', $dept)); ?>" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn-danger btn-sm" data-confirm="Delete <?php echo e($dept->name); ?>?">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-slate-400 py-6">No departments yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100"><?php echo e($departments->links()); ?></div>
    </div>

    <!-- Add form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Add Department</h3>
        <form method="POST" action="<?php echo e(route('admin.departments.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="form-label">Department Name *</label>
                <input type="text" name="name" class="form-input" required value="<?php echo e(old('name')); ?>">
            </div>
            <div>
                <label class="form-label">Code *</label>
                <input type="text" name="code" class="form-input" required maxlength="20" value="<?php echo e(old('code')); ?>">
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3"><?php echo e(old('description')); ?></textarea>
            </div>
            <button type="submit" class="btn-primary">Add Department</button>
        </form>
    </div>
</div>

<!-- Edit modal (hidden) -->
<div id="editDeptModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-semibold text-slate-800 mb-4">Edit Department</h3>
        <form id="editDeptForm" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div><label class="form-label">Name</label><input type="text" name="name" id="editDeptName" class="form-input" required></div>
            <div><label class="form-label">Code</label><input type="text" name="code" id="editDeptCode" class="form-input" required></div>
            <div><label class="form-label">Description</label><textarea name="description" id="editDeptDesc" class="form-input" rows="2"></textarea></div>
            <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="editDeptActive" value="1" class="rounded"><label for="editDeptActive" class="form-label mb-0">Active</label></div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Update</button>
                <button type="button" onclick="closeEditDept()" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function openEditDept(id, name, code, desc, active) {
    document.getElementById('editDeptForm').action = '/admin/departments/' + id;
    document.getElementById('editDeptName').value = name;
    document.getElementById('editDeptCode').value = code;
    document.getElementById('editDeptDesc').value = desc;
    document.getElementById('editDeptActive').checked = active === 1;
    document.getElementById('editDeptModal').classList.remove('hidden');
}
function closeEditDept() { document.getElementById('editDeptModal').classList.add('hidden'); }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/departments/index.blade.php ENDPATH**/ ?>