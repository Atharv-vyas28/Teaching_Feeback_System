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

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/faculty/attendance/partials/student-list.blade.php ENDPATH**/ ?>