<?php $__env->startSection('title', 'Feedback Sessions Overview'); ?>

<?php
    $header = 'Feedback Sessions';
    $subheader = 'Review anonymous feedback and release it to faculty.';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>All Feedback Sessions</h3>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#DCFCE7;color:#166534;padding:12px 16px;border-radius:8px;margin:16px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="background:#FEE2E2;color:#991B1B;padding:12px 16px;border-radius:8px;margin:16px;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course & Topic</th>
                    <th>Assigned Faculty</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Release Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $faculty = $session->classSession?->section?->faculty?->first();
                    ?>

                    <tr>
                        <td>
                            <div style="font-weight:600;">
                                <?php echo e($session->classSession?->section?->course?->name ?? 'Unknown course'); ?>

                            </div>

                            <div style="font-size:0.8rem;color:#64748B;">
                                <?php echo e($session->classSession?->topic ?? 'No topic'); ?>

                            </div>
                        </td>

                        <td>
                            <?php echo e($faculty?->name ?? 'Unassigned'); ?>

                        </td>

                        <td>
                            <?php echo e($session->classSession?->session_date?->format('M d, Y') ?? 'N/A'); ?>

                        </td>

                        <td>
                            <span class="badge badge-<?php echo e([
                                'draft' => 'gray',
                                'active' => 'green',
                                'closed' => 'blue'
                            ][$session->status] ?? 'gray'); ?>">
                                <?php echo e(ucfirst($session->status)); ?>

                            </span>
                        </td>

                        <td>
                            <?php if($session->isReleased()): ?>
                                <span class="badge badge-green">
                                    Released
                                </span>
                            <?php else: ?>
                                <form
                                    method="POST"
                                    action="<?php echo e(route('admin.feedback-sessions.release', $session)); ?>"
                                    style="display:inline;"
                                    onsubmit="return confirm('Release anonymous feedback to the assigned faculty? This will close the feedback session.')"
                                >
                                    <?php echo csrf_field(); ?>

                                    <button
                                        type="submit"
                                        class="badge badge-yellow"
                                        style="border:0;cursor:pointer;"
                                        title="Release anonymous feedback to faculty"
                                    >
                                        Not Released · Click to Release
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a
                                href="<?php echo e(route('admin.feedback-sessions.responses', $session)); ?>"
                                class="btn-secondary btn-sm"
                            >
                                View Feedback
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:24px;">
                            No feedback sessions found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($sessions->hasPages()): ?>
        <div style="margin:20px;">
            <?php echo e($sessions->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/feedback-sessions/index.blade.php ENDPATH**/ ?>