<?php $__env->startSection('title', 'Enrollments'); ?>
<?php $header = 'Student Enrollments'; $subheader = 'Manage student enrollments and assign faculty to sections.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    
    <div class="card">
        <div class="card-header"><h3>Enroll Student</h3></div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.enrollments.store')); ?>">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Student</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select student...</option>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->roll_number); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->course->name); ?> - Sec <?php echo e($s->section_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem->id); ?>"><?php echo e($sem->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Enroll Student</button>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header"><h3>Assign Faculty</h3></div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.faculty.assign')); ?>">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Faculty</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select faculty...</option>
                        <?php $__currentLoopData = \App\Models\User::where('role','faculty')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($f->id); ?>"><?php echo e($f->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->course->name); ?> - Sec <?php echo e($s->section_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem->id); ?>"><?php echo e($sem->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Assign Faculty</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Recent Enrollments</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course Section</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:600;color:#0F172A;"><?php echo e($e->student->name ?? 'N/A'); ?> <span style="font-size:0.75rem;color:#94A3B8;"><?php echo e($e->student->roll_number ?? ''); ?></span></td>
                    <td><?php echo e($e->section->course->name ?? 'N/A'); ?> - <?php echo e($e->section->section_name ?? 'N/A'); ?></td>
                    <td><?php echo e($e->semester->name ?? 'N/A'); ?></td>
                    <td><span class="badge <?php echo e($e->status === 'active' ? 'badge-green' : 'badge-gray'); ?>"><?php echo e(ucfirst($e->status)); ?></span></td>
                    <td><?php echo e(\Carbon\Carbon::parse($e->enrolled_at)->format('M d, Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">No enrollments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($enrollments->hasPages()): ?>
    <div style="padding:16px;"><?php echo e($enrollments->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/enrollments/index.blade.php ENDPATH**/ ?>