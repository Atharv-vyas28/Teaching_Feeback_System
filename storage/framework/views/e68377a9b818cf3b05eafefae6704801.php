blade


<?php $__env->startSection('title', 'Feedback Sessions'); ?>

<?php
    $header = 'Feedback Sessions';

    if (($level ?? 'semester') === 'semester') {
        $subheader = 'Select a semester to manage course feedback.';
    } elseif (($level ?? '') === 'department') {
        $subheader = 'Select a department to view its courses.';
    } else {
        $subheader = 'Manage feedback sessions for courses.';
    }
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card">

        
        <div class="card-header">

            <div>
                <h3>Feedback Sessions</h3>

                <p style="margin-top:4px;color:#64748B;font-size:.9rem;">
                    <?php echo e($subheader); ?>

                </p>
            </div>

            <?php if(($level ?? 'semester') === 'department'): ?>
                <a href="<?php echo e(route('admin.feedback-sessions.index')); ?>" class="btn-secondary btn-sm">
                    ← Semesters
                </a>
            <?php elseif(($level ?? '') === 'course'): ?>
                <a href="<?php echo e(route('admin.feedback-sessions.departments', $semester)); ?>" class="btn-secondary btn-sm">
                    ← Departments
                </a>
            <?php elseif(($level ?? '') === 'faculty'): ?>
                <a href="<?php echo e(route('admin.feedback-sessions.courses', [
                    'semester' => $semester,
                    'department' => $department,
                ])); ?>"
                    class="btn-secondary btn-sm">
                    ← Courses
                </a>
            <?php endif; ?>

        </div>


        
        <?php if(session('success')): ?>
            <div
                style="
            background:#DCFCE7;
            color:#166534;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div
                style="
            background:#FEE2E2;
            color:#991B1B;
            padding:12px 16px;
            border-radius:8px;
            margin:16px;
        ">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>


        
        <?php if(($level ?? 'semester') === 'semester'): ?>

            <div
                style="
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
            gap:16px;
            padding:20px;
        ">

                <?php $__empty_1 = true; $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('admin.feedback-sessions.departments', $semester)); ?>"
                        style="
                        display:block;
                        text-decoration:none;
                        color:inherit;
                        border:1px solid #E2E8F0;
                        border-radius:12px;
                        padding:20px;
                        background:#FFFFFF;
                        transition:all .2s ease;
                    "
                        onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">

                        <div
                            style="
                        display:flex;
                        justify-content:space-between;
                        align-items:flex-start;
                        gap:12px;
                    ">

                            <div>

                                <div
                                    style="
                                font-size:1.05rem;
                                font-weight:600;
                                color:#0F172A;
                            ">
                                    <?php echo e($semester->name); ?>

                                </div>

                                <div
                                    style="
                                margin-top:5px;
                                font-size:.82rem;
                                color:#64748B;
                            ">
                                    Academic Year:
                                    <?php echo e($semester->academicYear?->name ?? 'N/A'); ?>

                                </div>

                            </div>

                            <?php if($semester->is_current): ?>
                                <span class="badge badge-green">
                                    Current
                                </span>
                            <?php endif; ?>

                        </div>

                    </a>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div
                        style="
                    grid-column:1/-1;
                    text-align:center;
                    padding:40px 20px;
                    color:#64748B;
                ">
                        No semesters found.
                    </div>
                <?php endif; ?>

            </div>


            
        <?php elseif(($level ?? '') === 'department'): ?>
            <div style="padding:20px;">

                
                <div
                    style="
                margin-bottom:18px;
                font-size:.85rem;
                color:#64748B;
            ">
                    <a href="<?php echo e(route('admin.feedback-sessions.index')); ?>" style="color:#0F172A;text-decoration:none;">
                        Semesters
                    </a>

                    <span style="margin:0 6px;">/</span>
                    <strong style="color:#0F172A;">
                        <?php echo e($semester->name); ?>

                    </strong>
                </div>


                <div
                    style="
                display:grid;
                grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
                gap:16px;
            ">

                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('admin.feedback-sessions.courses', [
                            'semester' => $semester,
                            'department' => $department,
                        ])); ?>"
                            style="
                            display:block;
                            text-decoration:none;
                            color:inherit;
                            border:1px solid #E2E8F0;
                            border-radius:12px;
                            padding:20px;
                            background:#FFFFFF;
                            transition:all .2s ease;
                        "
                            onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">

                            <div
                                style="
                            font-size:1.05rem;
                            font-weight:600;
                            color:#0F172A;
                        ">
                                <?php echo e($department->name); ?>

                            </div>

                            <div
                                style="
                            margin-top:8px;
                            font-size:.85rem;
                            color:#64748B;
                        ">
                                <?php echo e($department->semester_courses_count ?? 0); ?>

                                courses
                            </div>

                        </a>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div
                            style="
                        grid-column:1/-1;
                        text-align:center;
                        padding:40px 20px;
                        color:#64748B;
                    ">
                            No departments found for this semester.
                        </div>
                    <?php endif; ?>

                </div>

            </div>


            
        <?php elseif(($level ?? '') === 'course'): ?>
            <div style="padding:20px;">

                
                <div
                    style="
            margin-bottom:20px;
            font-size:.85rem;
            color:#64748B;
        ">
                    <a href="<?php echo e(route('admin.feedback-sessions.index')); ?>" style="color:#0F172A;text-decoration:none;">
                        Semesters
                    </a>

                    <span style="margin:0 6px;">/</span>

                    <a href="<?php echo e(route('admin.feedback-sessions.departments', $semester)); ?>"
                        style="color:#0F172A;text-decoration:none;">
                        <?php echo e($semester->name); ?>

                    </a>

                    <span style="margin:0 6px;">/</span>

                    <strong style="color:#0F172A;">
                        <?php echo e($department->name); ?>

                    </strong>
                </div>


                
                <div style="
            display:flex;
            flex-direction:column;
            gap:12px;
        ">

                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('admin.feedback-sessions.faculty', [
                            'semester' => $semester,
                            'department' => $department,
                            'course' => $course->id,
                        ])); ?>"
                            style="
                            display:block;
                            text-decoration:none;
                            color:inherit;
                            border:1px solid #E2E8F0;
                            border-radius:12px;
                            padding:20px;
                            background:#FFFFFF;
                            transition:all .2s ease;
                        "
                            onmouseover="this.style.borderColor='#94A3B8';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#E2E8F0';this.style.transform='translateY(0)'">


                            <div
                                style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:20px;
                        flex-wrap:wrap;
                    ">

                                
                                <div>

                                    <div
                                        style="
                                font-weight:600;
                                font-size:1rem;
                                color:#0F172A;
                            ">
                                        <?php echo e($course->code); ?>


                                        <span
                                            style="
                                    font-weight:400;
                                    color:#475569;
                                ">
                                            — <?php echo e($course->name); ?>

                                        </span>
                                    </div>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div
                            style="
                    text-align:center;
                    padding:40px 20px;
                    color:#64748B;
                ">
                            No courses found for this department.
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        <?php elseif(($level ?? '') === 'faculty'): ?>
            <div style="padding:20px;">

                
                <div
                    style="
                display:flex;
                align-items:center;
                flex-wrap:wrap;
                gap:6px;
                margin-bottom:22px;
                font-size:.85rem;
                color:#64748B;
            ">

                    <a href="<?php echo e(route('admin.feedback-sessions.index')); ?>"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        Semesters
                    </a>

                    <span>/</span>

                    <a href="<?php echo e(route('admin.feedback-sessions.departments', $semester)); ?>"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        <?php echo e($semester->name); ?>

                    </a>

                    <span>/</span>

                    <a href="<?php echo e(route('admin.feedback-sessions.courses', [
                        'semester' => $semester,
                        'department' => $department,
                    ])); ?>"
                        style="
                    color:#475569;
                    text-decoration:none;
                ">
                        <?php echo e($department->name); ?>

                    </a>

                    <span>/</span>

                    <strong style="color:#0F172A;">
                        <?php echo e($course->code); ?>

                    </strong>

                </div>


                
                <div style="
                margin-bottom:22px;
            ">

                    <div
                        style="
                    display:flex;
                    justify-content:space-between;
                    align-items:flex-start;
                    gap:16px;
                    flex-wrap:wrap;
                ">

                        <div>

                            <div
                                style="
                            font-size:1.25rem;
                            font-weight:650;
                            color:#0F172A;
                        ">
                                <?php echo e($course->code); ?>

                                <span
                                    style="
                                font-weight:400;
                                color:#475569;
                            ">
                                    — <?php echo e($course->name); ?>

                                </span>
                            </div>

                            <div
                                style="
                            margin-top:5px;
                            font-size:.85rem;
                            color:#64748B;
                        ">
                                Faculty teaching this course
                            </div>

                        </div>

                        <a href="<?php echo e(route('admin.feedback-sessions.courses', [
                            'semester' => $semester,
                            'department' => $department,
                        ])); ?>"
                            class="btn-secondary btn-sm">
                            ← Courses
                        </a>

                    </div>

                </div>


                
                <div
                    style="
                display:grid;
                grid-template-columns:repeat(3, minmax(0, 1fr));
                gap:12px;
                margin-bottom:24px;
            ">

                    
                    <div
                        style="
                    border:1px solid #E2E8F0;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#FFFFFF;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Remaining
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#94A3B8;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#0F172A;
                    ">
                            <?php echo e($remainingCount); ?>

                        </div>

                    </div>


                    
                    <div
                        style="
                    border:1px solid #BFDBFE;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#F8FBFF;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Ongoing
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#3B82F6;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#1D4ED8;
                    ">
                            <?php echo e($ongoingCount); ?>

                        </div>

                    </div>


                    
                    <div
                        style="
                    border:1px solid #BBF7D0;
                    border-radius:10px;
                    padding:14px 16px;
                    background:#F8FFF9;
                ">

                        <div
                            style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                            <span
                                style="
                            font-size:.82rem;
                            color:#64748B;
                        ">
                                Done
                            </span>

                            <span
                                style="
                            width:8px;
                            height:8px;
                            border-radius:50%;
                            background:#22C55E;
                        "></span>

                        </div>

                        <div
                            style="
                        margin-top:5px;
                        font-size:1.35rem;
                        font-weight:650;
                        color:#15803D;
                    ">
                            <?php echo e($doneCount); ?>

                        </div>

                    </div>

                </div>


                
                <div>

                    <div
                        style="
                    margin-bottom:10px;
                    font-size:.9rem;
                    font-weight:600;
                    color:#0F172A;
                ">
                        Faculty
                    </div>


                    <div
                        style="
                    display:flex;
                    flex-direction:column;
                    gap:10px;
                ">

                        <?php $__empty_1 = true; $__currentLoopData = $facultyList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $session = $item->feedbackSession;
                            ?>

                            <div
                                style="
                            border:1px solid #E2E8F0;
                            border-radius:11px;
                            padding:16px 18px;
                            background:#FFFFFF;
                        ">

                                <div
                                    style="
                                display:flex;
                                align-items:center;
                                justify-content:space-between;
                                gap:18px;
                                flex-wrap:wrap;
                            ">

                                    
                                    <div
                                        style="
                                    min-width:220px;
                                ">

                                        <div
                                            style="
                                        font-size:.98rem;
                                        font-weight:600;
                                        color:#0F172A;
                                    ">
                                            <?php echo e($item->faculty->name); ?>

                                        </div>

                                        <div
                                            style="
                                        margin-top:4px;
                                        display:flex;
                                        align-items:center;
                                        gap:8px;
                                        flex-wrap:wrap;
                                        font-size:.78rem;
                                        color:#64748B;
                                    ">

                                            <span>
                                                <?php echo e($item->faculty->employee_id ?? $item->faculty->email); ?>

                                            </span>

                                            <span>•</span>

                                            <span>
                                                Section <?php echo e($item->section->section_name ?? $item->section->name); ?>

                                            </span>

                                        </div>

                                    </div>


                                    
                                    <div
                                        style="
                                    display:flex;
                                    align-items:center;
                                    gap:10px;
                                    flex-wrap:wrap;
                                ">

                                        <?php if($item->feedback_status === 'not_taken'): ?>
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#F1F5F9;
                                            color:#475569;
                                        ">
                                                Remaining
                                            </span>

                                            <a href="<?php echo e(route('admin.feedback-sessions.open', [
                                                'section' => $item->section->id,
                                                'faculty' => $item->faculty->id,
                                            ])); ?>"
                                                class="btn-primary btn-sm">
                                                Open Feedback
                                            </a>
                                        <?php elseif($item->feedback_status === 'ongoing'): ?>
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#EFF6FF;
                                            color:#1D4ED8;
                                        ">
                                                Ongoing
                                            </span>

                                            <?php if($session): ?>
                                                <a href="<?php echo e(route('admin.feedback-sessions.responses', $session)); ?>"
                                                    class="btn-secondary btn-sm">
                                                    View Responses
                                                </a>
                                            <?php endif; ?>
                                        <?php elseif($item->feedback_status === 'completed'): ?>
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#ECFDF5;
                                            color:#15803D;
                                        ">
                                                Done
                                            </span>

                                            <?php if($session): ?>
                                                <a href="<?php echo e(route('admin.feedback-sessions.responses', $session)); ?>"
                                                    class="btn-secondary btn-sm">
                                                    View Results
                                                </a>
                                            <?php endif; ?>
                                        <?php elseif($item->feedback_status === 'awaiting_release'): ?>
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#FFFBEB;
                                            color:#A16207;
                                        ">
                                                Awaiting Release
                                            </span>

                                            <?php if($session): ?>
                                                <a href="<?php echo e(route('admin.feedback-sessions.responses', $session)); ?>"
                                                    class="btn-secondary btn-sm">
                                                    View Responses
                                                </a>
                                            <?php endif; ?>
                                        <?php elseif($item->feedback_status === 'scheduled'): ?>
                                            <span
                                                style="
                                            display:inline-flex;
                                            align-items:center;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            font-size:.75rem;
                                            font-weight:600;
                                            background:#F5F3FF;
                                            color:#6D28D9;
                                        ">
                                                Scheduled
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <div
                                style="
                            border:1px solid #E2E8F0;
                            border-radius:11px;
                            padding:36px 20px;
                            text-align:center;
                            background:#FFFFFF;
                            color:#64748B;
                            font-size:.9rem;
                        ">
                                No faculty members are assigned to this course.
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            </div>
        <?php endif; ?>


    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/admin/feedback-sessions/index.blade.php ENDPATH**/ ?>