<?php $__env->startSection('title', 'Feedback Questions'); ?>
<?php $header = 'Feedback Questions'; $subheader = 'Manage rating questions and weights'; ?>
<?php $__env->startSection('sidebar-nav'); ?> <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Questions list -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">Active Questions</h3>
            <?php $totalW = $questions->where('is_active',true)->where('type','rating')->sum('weight'); ?>
            <span class="badge <?php echo e(abs($totalW - 100) < 0.01 ? 'badge-green' : 'badge-red'); ?>">Total Weight: <?php echo e($totalW); ?>%</span>
        </div>
        <?php if(abs($totalW - 100) > 0.01): ?>
        <div class="mx-4 mt-4 alert alert-warning">⚠️ Active rating question weights should sum to 100%. Current total: <?php echo e($totalW); ?>%</div>
        <?php endif; ?>
        <div class="overflow-auto">
            <table class="data-table">
                <thead><tr><th>Order</th><th>Question</th><th>Type</th><th>Max</th><th>Weight</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="sortable-questions">
                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr data-id="<?php echo e($q->id); ?>">
                        <td class="cursor-move text-slate-400">☰ <?php echo e($q->display_order); ?></td>
                        <td><div class="max-w-xs text-sm"><?php echo e($q->question_text); ?></div><div class="text-xs text-slate-400"><?php echo e($q->is_required ? 'Required' : 'Optional'); ?></div></td>
                        <td><span class="badge badge-blue"><?php echo e(ucfirst($q->type)); ?></span></td>
                        <td><?php echo e($q->max_marks); ?></td>
                        <td><?php if($q->type==='rating'): ?><span class="badge badge-purple"><?php echo e($q->weight); ?>%</span><?php else: ?><span class="text-slate-400 text-xs">N/A</span><?php endif; ?></td>
                        <td><span class="badge <?php echo e($q->is_active ? 'badge-green' : 'badge-gray'); ?>"><?php echo e($q->is_active ? 'Active' : 'Inactive'); ?></span></td>
                        <td>
                            <div class="flex gap-2">
                                <button onclick="openEditQ(<?php echo e($q->id); ?>, <?php echo e(json_encode($q->question_text)); ?>, '<?php echo e($q->type); ?>', <?php echo e($q->max_marks); ?>, <?php echo e($q->weight); ?>, <?php echo e($q->display_order); ?>, <?php echo e($q->is_active?1:0); ?>, <?php echo e($q->is_required?1:0); ?>)" class="btn-secondary btn-sm">Edit</button>
                                <form method="POST" action="<?php echo e(route('admin.feedback-questions.toggle', $q)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn-warning btn-sm"><?php echo e($q->is_active ? 'Disable' : 'Enable'); ?></button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.feedback-questions.destroy', $q)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn-danger btn-sm" data-confirm="Delete this question?">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 h-fit">
        <h3 class="font-semibold text-slate-800 mb-4">Add Question</h3>
        <form method="POST" action="<?php echo e(route('admin.feedback-questions.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="form-label">Question Text *</label>
                <textarea name="question_text" class="form-input" rows="3" required><?php echo e(old('question_text')); ?></textarea>
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="rating">Rating (1-5)</option>
                    <option value="text">Text</option>
                    <option value="multiple_choice">Multiple Choice</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Max Marks</label>
                    <input type="number" name="max_marks" value="5" class="form-input" step="0.5" min="1" max="10">
                </div>
                <div>
                    <label class="form-label">Weight (%)</label>
                    <input type="number" name="weight" value="0" class="form-input" step="1" min="0" max="100">
                </div>
            </div>
            <div>
                <label class="form-label">Display Order</label>
                <input type="number" name="display_order" value="<?php echo e($questions->count() + 1); ?>" class="form-input" min="1">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_required" id="is_req" value="1" checked class="rounded">
                <label for="is_req" class="form-label mb-0">Required</label>
            </div>
            <button type="submit" class="btn-primary w-full">Add Question</button>
        </form>
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editQModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-semibold text-slate-800 mb-4">Edit Question</h3>
        <form id="editQForm" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div><label class="form-label">Question Text</label><textarea name="question_text" id="editQText" class="form-input" rows="3" required></textarea></div>
            <div><label class="form-label">Type</label>
                <select name="type" id="editQType" class="form-select">
                    <option value="rating">Rating</option>
                    <option value="text">Text</option>
                    <option value="multiple_choice">Multiple Choice</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="form-label">Max Marks</label><input type="number" name="max_marks" id="editQMax" class="form-input" step="0.5"></div>
                <div><label class="form-label">Weight (%)</label><input type="number" name="weight" id="editQWeight" class="form-input" step="1"></div>
            </div>
            <div><label class="form-label">Display Order</label><input type="number" name="display_order" id="editQOrder" class="form-input"></div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="editQActive" value="1" class="rounded"><label for="editQActive" class="form-label mb-0">Active</label></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_required" id="editQReq" value="1" class="rounded"><label for="editQReq" class="form-label mb-0">Required</label></div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Update</button>
                <button type="button" onclick="document.getElementById('editQModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function openEditQ(id, text, type, max, weight, order, active, req) {
    document.getElementById('editQForm').action = '/admin/feedback-questions/' + id;
    document.getElementById('editQText').value = text;
    document.getElementById('editQType').value = type;
    document.getElementById('editQMax').value = max;
    document.getElementById('editQWeight').value = weight;
    document.getElementById('editQOrder').value = order;
    document.getElementById('editQActive').checked = active === 1;
    document.getElementById('editQReq').checked = req === 1;
    document.getElementById('editQModal').classList.remove('hidden');
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/feedback-questions/index.blade.php ENDPATH**/ ?>