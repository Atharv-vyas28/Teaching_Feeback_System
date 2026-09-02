<?php if($students->isEmpty()): ?>
    <div style="text-align:center;padding:28px;color:#94A3B8;">
        No students found in this list.
    </div>
<?php else: ?>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th>Attendance</th>
                    <th>Feedback Status</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($attendance->student?->roll_number ?? 'N/A'); ?>

                        </td>

                        <td>
                            <?php echo e($attendance->student?->name ?? 'Unknown Student'); ?>

                        </td>

                        <td>
                            <span class="badge badge-<?php echo e(in_array($attendance->status, ['present', 'late']) ? 'green' : 'red'); ?>">
                                <?php echo e(ucfirst($attendance->status)); ?>

                            </span>
                        </td>

                        <td>
                            <?php if($attendance->feedback_enabled): ?>
                                <span class="badge badge-blue">
                                    Enabled
                                </span>
                            <?php else: ?>
                                <span class="badge badge-gray">
                                    Not Enabled
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/faculty/attendance/partials/student-list.blade.php ENDPATH**/ ?>