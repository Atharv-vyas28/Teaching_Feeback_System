<?php $__env->startSection('title', 'Student Directory'); ?>

<?php
    $header = 'Student Directory';
    $subheader = 'Find students by branch, programme, course, and semester.';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Students</h3>

        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary btn-sm">
            Add Student
        </a>
    </div>

    <div class="card-body">
        <form
            method="GET"
            action="<?php echo e(route('admin.students.directory')); ?>"
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:22px;"
        >
            <div>
                <label class="form-label">Branch / Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>

                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($department->id); ?>"
                            <?php if($filters['department_id'] == $department->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($department->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Programme</label>
                <select name="program_id" class="form-select">
                    <option value="">All Programmes</option>

                    <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($program->id); ?>"
                            <?php if($filters['program_id'] == $program->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($program->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>

                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($course->id); ?>"
                            <?php if($filters['course_id'] == $course->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($course->code); ?> — <?php echo e($course->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-select">
                    <option value="">All Semesters</option>

                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($semester->id); ?>"
                            <?php if($filters['semester_id'] == $semester->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($semester->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="form-label">Search</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    value="<?php echo e($filters['search']); ?>"
                    placeholder="Name, email, or roll number"
                >
            </div>

            <div style="display:flex;align-items:end;gap:8px;">
                <button type="submit" class="btn-primary">
                    Filter Students
                </button>

                <a
                    href="<?php echo e(route('admin.students.directory')); ?>"
                    class="btn-secondary"
                >
                    Reset
                </a>
            </div>
        </form>

        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Student</th>
                        <th>Branch</th>
                        <th>Programme</th>
                        <th>Current Semester</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($student->roll_number ?? 'Not assigned'); ?>

                                </strong>
                            </td>

                            <td>
                                <div style="font-weight:700;color:#0F172A;">
                                    <?php echo e($student->name); ?>

                                </div>

                                <div style="font-size:.76rem;color:#64748B;">
                                    <?php echo e($student->email); ?>

                                </div>
                            </td>

                            <td>
                                <?php echo e($student->department?->name ?? 'Not assigned'); ?>

                            </td>

                            <td>
                                <?php echo e($student->program?->name ?? 'Not assigned'); ?>

                            </td>

                            <td>
                                Semester <?php echo e($student->current_semester ?? '—'); ?>

                            </td>

                            <td>
                                <a
                                    href="<?php echo e(route('admin.students.details', $student)); ?>"
                                    class="btn-secondary btn-sm"
                                >
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:38px;color:#94A3B8;">
                                No students match the selected branch, programme,
                                course, or semester.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($students->hasPages()): ?>
            <div style="margin-top:18px;">
                <?php echo e($students->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/admin/students/directory.blade.php ENDPATH**/ ?>