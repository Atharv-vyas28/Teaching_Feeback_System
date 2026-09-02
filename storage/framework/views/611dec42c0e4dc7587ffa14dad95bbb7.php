<?php $__env->startSection('title', 'Ratings Report'); ?>
<?php $header = 'Faculty Ratings Report'; $subheader = 'Aggregated faculty performance ratings based on student feedback.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.reports.ratings')); ?>" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-select" style="width:220px;">
                    <option value="">All Semesters</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sem->id); ?>" <?php echo e($semesterId == $sem->id ? 'selected' : ''); ?>><?php echo e($sem->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Faculty Performance Rankings</h3>
        <span class="badge badge-blue"><?php echo e(count($ratings)); ?> results</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Faculty Member</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Rating</th>
                    <th>Responses</th>
                    <th>Status</th>
                    <th>Calculated</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:700;color:#94A3B8;"><?php echo e($i + 1); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#1D4ED8;flex-shrink:0;">
                                <?php echo e(strtoupper(substr($r->faculty->name ?? 'F', 0, 1))); ?>

                            </div>
                            <span style="font-weight:600;"><?php echo e($r->faculty->name ?? 'N/A'); ?></span>
                        </div>
                    </td>
                    <td><?php echo e($r->section->course->name ?? 'N/A'); ?></td>
                    <td><?php echo e($r->semester->name ?? 'N/A'); ?></td>
                    <td>
                        <?php if($r->overall_weighted_rating): ?>
                        <span style="font-size:1rem;font-weight:800;color:<?php echo e($r->overall_weighted_rating >= 4 ? '#1D4ED8' : ($r->overall_weighted_rating >= 3 ? '#D97706' : '#DC2626')); ?>;">
                            <?php echo e(number_format($r->overall_weighted_rating, 1)); ?>

                        </span>
                        <span style="color:#94A3B8;font-size:0.75rem;">/ 5</span>
                        <?php else: ?>
                        <span style="color:#94A3B8;">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($r->response_count); ?></td>
                    <td>
                        <?php if($r->overall_weighted_rating >= 4): ?>
                            <span class="badge badge-green">Excellent</span>
                        <?php elseif($r->overall_weighted_rating >= 3): ?>
                            <span class="badge badge-yellow">Stable</span>
                        <?php elseif($r->overall_weighted_rating): ?>
                            <span class="badge badge-red">Needs Improvement</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#94A3B8;font-size:0.8rem;">
                        <?php echo e($r->calculated_at ? \Carbon\Carbon::parse($r->calculated_at)->format('M d, Y') : '—'); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="empty-state" style="text-align:center;padding:40px;color:#94A3B8;">No rating results found for selected filters.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/reports/ratings.blade.php ENDPATH**/ ?>