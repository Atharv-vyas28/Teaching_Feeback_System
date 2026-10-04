blade


<?php $__env->startSection('title', 'Open Feedback'); ?>

<?php
    $header = 'Open Feedback';
    $subheader = 'Schedule teaching feedback for this course.';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card">

        <div class="card-header">
            <div>
                <h3>Open Feedback</h3>

                <p
                    style="
                margin-top:4px;
                color:#64748B;
                font-size:.9rem;
            ">
                    Schedule the feedback session and assign a feedback staff member.
                </p>
            </div>

            <a href="<?php echo e(route('admin.feedback-sessions.faculty', [
                'semester' => $section->semester_id,
                'department' => $section->course->department_id,
                'course' => $section->course->id,
            ])); ?>"
                class="btn-secondary btn-sm">
                ← Back
            </a>
        </div>


        <?php if($errors->any()): ?>
            <div
                style="
            background:#FEE2E2;
            color:#991B1B;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                <ul style="margin:0;padding-left:20px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        
        <div
            style="
        margin:20px;
        padding:18px;
        border:1px solid #E2E8F0;
        border-radius:12px;
        background:#F8FAFC;
    ">

            <div style="
            font-size:.8rem;
            color:#64748B;
            margin-bottom:6px;
        ">
                Course
            </div>

            <div style="
            font-size:1.1rem;
            font-weight:600;
            color:#0F172A;
        ">
                <?php echo e($section->course->code); ?>


                <span style="font-weight:400;color:#475569;">
                    — <?php echo e($section->course->name); ?>

                </span>
            </div>

            <div style="
            margin-top:8px;
            font-size:.85rem;
            color:#64748B;
        ">
                Section:

                <strong style="color:#334155;">
                    <?php echo e($section->section_name ?? ($section->name ?? 'N/A')); ?>

                </strong>
            </div>

            <div style="
            margin-top:5px;
            font-size:.85rem;
            color:#64748B;
        ">
                Faculty:

                <strong style="color:#334155;">
                    <?php echo e($faculty->name); ?>

                </strong>
            </div>

        </div>


        

        <form method="POST"
            action="<?php echo e(route('admin.feedback-sessions.open.store', [
                'section' => $section,
                'faculty' => $faculty,
            ])); ?>"
            style="padding:0 20px 20px;">

            <?php echo csrf_field(); ?>


            
            <div style="margin-bottom:20px;">

                <label
                    style="
            display:block;
            margin-bottom:10px;
            font-weight:600;
            color:#334155;
        ">
                    Feedback Staff
                </label>

                <div
                    style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
        gap:10px;
    ">

                    <?php $__empty_1 = true; $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label
                            style="
                display:flex;
                align-items:center;
                gap:10px;
                padding:12px 14px;
                border:1px solid #E2E8F0;
                border-radius:8px;
                cursor:pointer;
                background:#FFFFFF;
            ">

                            <input type="checkbox" name="staff_ids[]" value="<?php echo e($staff->id); ?>"
                                <?php if(in_array($staff->id, old('staff_ids', []))): echo 'checked'; endif; ?>>

                            <span>
                                <?php echo e($staff->name); ?>

                            </span>

                        </label>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div
                            style="
                color:#64748B;
                font-size:.9rem;
                padding:10px 0;
            ">
                            No active staff members are available for this department.
                        </div>
                    <?php endif; ?>

                </div>

                <div style="
        margin-top:7px;
        font-size:.78rem;
        color:#64748B;
    ">
                    Select one or more staff members. They will be able to take attendance
                    for this feedback session.
                </div>

            </div>


            
            <div
                style="
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
            gap:16px;
            margin-bottom:20px;
        ">

                <div>

                    <label for="opened_date"
                        style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                        Start Date
                    </label>

                    <input type="date" id="opened_date" class="form-input" required>

                </div>


                <div>

                    <label for="opened_time"
                        style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                        Start Time
                    </label>

                    <input type="time" id="opened_time" class="form-input" required>

                </div>

            </div>


            
            <input type="hidden" name="opened_at" id="opened_at" value="<?php echo e(old('opened_at')); ?>">


            
            <div style="margin-bottom:24px;">

                <label
                    style="
                    display:block;
                    margin-bottom:10px;
                    font-weight:600;
                    color:#334155;
                ">
                    Feedback Duration
                </label>

                <div
                    style="
                display:grid;
                grid-template-columns:repeat(auto-fit, minmax(120px, 1fr));
                gap:10px;
            ">

                    <?php $__currentLoopData = [30, 60, 90, 120]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label
                            style="
                            display:flex;
                            align-items:center;
                            gap:8px;
                            padding:12px 14px;
                            border:1px solid #E2E8F0;
                            border-radius:8px;
                            cursor:pointer;
                            background:#FFFFFF;
                        ">

                            <input type="radio" name="duration_minutes" value="<?php echo e($duration); ?>"
                                <?php if(old('duration_minutes', 60) == $duration): echo 'checked'; endif; ?> required>

                            <span>
                                <?php echo e($duration); ?> minutes
                            </span>

                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <div
                    style="
                margin-top:7px;
                font-size:.78rem;
                color:#64748B;
            ">
                    The feedback session will automatically end after the selected duration.
                </div>

            </div>


            
            <div style="
            display:flex;
            justify-content:flex-end;
            gap:10px;
        ">

                <a href="<?php echo e(route('admin.feedback-sessions.faculty', [
                    'semester' => $section->semester_id,
                    'department' => $section->course->department_id,
                    'course' => $section->course->id,
                ])); ?>"
                    class="btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    Open Feedback
                </button>

            </div>

        </form>

    </div>


    
    <script>
        const form = document.querySelector('form');
        const dateInput = document.getElementById('opened_date');
        const timeInput = document.getElementById('opened_time');
        const openedAtInput = document.getElementById('opened_at');

        function updateOpenedAt() {
            if (dateInput.value && timeInput.value) {
                openedAtInput.value =
                    dateInput.value + ' ' + timeInput.value;
            }
        }

        dateInput.addEventListener('change', updateOpenedAt);
        timeInput.addEventListener('change', updateOpenedAt);

        form.addEventListener('submit', function(event) {
            updateOpenedAt();

            const selectedStaff = document.querySelectorAll(
                'input[name="staff_ids[]"]:checked'
            );

            if (selectedStaff.length === 0) {
                event.preventDefault();
                alert('Please select at least one feedback staff member.');
                return;
            }

            if (!openedAtInput.value) {
                event.preventDefault();
                alert('Please select the start date and time.');
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/admin/feedback-sessions/open.blade.php ENDPATH**/ ?>