

<?php $__env->startSection('title', 'Student Profile'); ?>

<?php
    $header = 'Student Profile';
    $subheader = 'Academic record, programme information, and course enrollments';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    .student-profile-page {
        max-width: 1250px;
        margin: 0 auto;
        padding-bottom: 20px;
    }

    .student-profile-banner {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4e9f2;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(31, 54, 88, .06);
    }

    .student-profile-cover {
      
        height: 40px;
        background: linear-gradient(115deg, #ffffff, #6d5dfc 55%, #ffffff);
    }

    .student-profile-info {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        padding: 0 22px 16px;
    }

    .student-profile-user {
        display: flex;
        align-items: flex-end;
        gap: 13px;
        margin-top: -32px;
    }

    .student-avatar {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        border-radius: 15px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        font-size: 24px;
        font-weight: 800;
        box-shadow: 0 6px 14px rgba(79, 70, 229, .22);
        flex-shrink: 0;
    }

    .student-name {
        margin: 0;
        color: #132445;
        font-size: 21px;
        line-height: 1.2;
        font-weight: 800;
    }

    .student-email {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .back-directory-button {
        display: inline-block;
        padding: 9px 13px;
        border: 1px solid #d9ddff;
        border-radius: 9px;
        background: #f4f3ff;
        color: #5346df;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
        white-space: nowrap;
    }

    .back-directory-button:hover {
        background: #e8e7ff;
        transform: translateY(-1px);
    }

    /* =========================
       INFORMATION CARDS
       ========================= */
    .student-info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 14px;
    }

    .student-info-card {
        min-height: 108px;
        padding: 15px 17px;
        border: 1px solid #e6eaf2;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(31, 54, 88, .04);
    }

    .student-info-card.blue {
        border-color: #dbeafe;
        background: linear-gradient(145deg, #eff6ff, #fff);
    }

    .student-info-card.purple {
        border-color: #e9ddff;
        background: linear-gradient(145deg, #f7f3ff, #fff);
    }

    .student-info-card.green {
        border-color: #d4f4e5;
        background: linear-gradient(145deg, #effdf6, #fff);
    }

    .student-info-icon {
        display: flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 16px;
    }

    .blue .student-info-icon {
        background: #dbeafe;
        color: #2563eb;
    }

    .purple .student-info-icon {
        background: #ede9fe;
        color: #7c3aed;
    }

    .green .student-info-icon {
        background: #d1fae5;
        color: #059669;
    }

    .student-info-label {
        margin: 9px 0 3px;
        color: #7b89a5;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .student-info-value {
        margin: 0;
        color: #1b2d4d;
        font-size: 14px;
        line-height: 1.3;
        font-weight: 750;
    }

    /* =========================
       COURSE ENROLLMENTS
       ========================= */
    .student-enrollment-card {
        margin-top: 14px;
        overflow: hidden;
        border: 1px solid #e4e9f2;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(31, 54, 88, .05);
    }

    .student-enrollment-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .student-enrollment-title {
        margin: 0;
        color: #132445;
        font-size: 16px;
        font-weight: 800;
    }

    .student-enrollment-subtitle {
        margin: 3px 0 0;
        color: #73809a;
        font-size: 12px;
    }

    .course-count {
        padding: 6px 10px;
        border-radius: 999px;
        background: #edeaff;
        color: #5a4ce0;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .student-table-wrap {
        overflow-x: auto;
    }

    .student-enrollment-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
    }

    .student-enrollment-table th {
        padding: 10px 16px;
        background: #f8faff;
        color: #91a0bc;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .06em;
        text-align: left;
        text-transform: uppercase;
    }

    .student-enrollment-table td {
        padding: 11px 16px;
        border-top: 1px solid #edf0f5;
        color: #435572;
        font-size: 13px;
    }

    .student-enrollment-table tr:hover td {
        background: #fafaff;
    }

    .course-title {
        color: #1d3154;
        font-weight: 750;
    }

    .course-code {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 5px;
        background: #eef2f7;
        color: #54647e;
        font-family: monospace;
        font-size: 11px;
        font-weight: 700;
    }

    .active-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 999px;
        background: #dcfce7;
        color: #16854c;
        font-size: 11px;
        font-weight: 800;
    }

    .no-enrollment {
        padding: 38px 20px !important;
        color: #71809a !important;
        text-align: center;
    }

    /* =========================
       RESPONSIVE
       ========================= */
    @media (max-width: 900px) {
        .student-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .student-profile-info,
        .student-profile-user {
            align-items: flex-start;
            flex-direction: column;
        }

        .student-profile-info {
            padding: 0 16px 15px;
        }

        .student-profile-user {
            margin-top: -28px;
        }

        .student-info-grid {
            grid-template-columns: 1fr;
        }

        .student-enrollment-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-directory-button {
            width: 100%;
            text-align: center;
        }
    }
</style>



<div class="student-profile-page">

    <div class="student-profile-banner">
        <div class="student-profile-cover"></div>

        <div class="student-profile-info">
            <div class="student-profile-user">
                <div class="student-avatar">
                    <?php echo e(strtoupper(substr($student->name ?? 'S', 0, 1))); ?>

                </div>

                <div>
                    <h2 class="student-name"><?php echo e($student->name); ?></h2>
                    <p class="student-email"><?php echo e($student->email); ?></p>
                </div>
            </div>

            <a href="<?php echo e(route('admin.students.directory')); ?>" class="back-directory-button">
                ← Back to Student Directory
            </a>
        </div>
    </div>

    <div class="student-info-grid">
        <div class="student-info-card blue">
            <div class="student-info-icon">⌂</div>
            <p class="student-info-label">Department / Branch</p>
            <p class="student-info-value">
                <?php echo e($student->department->name ?? 'Not assigned'); ?>

            </p>
        </div>

        <div class="student-info-card purple">
            <div class="student-info-icon">◆</div>
            <p class="student-info-label">Programme</p>
            <p class="student-info-value">
                <?php echo e($student->program->name ?? 'Not assigned'); ?>

            </p>
        </div>

        <div class="student-info-card green">
            <div class="student-info-icon">#</div>
            <p class="student-info-label">Student ID / Roll Number</p>
            <p class="student-info-value">
                <?php echo e($student->roll_number ?? $student->id); ?>

            </p>
        </div>
    </div>

    <div class="student-enrollment-card">
        <div class="student-enrollment-head">
            <div>
                <h3 class="student-enrollment-title">Course Enrollments</h3>
                <p class="student-enrollment-subtitle">
                    Courses currently assigned to this student.
                </p>
            </div>

            <span class="course-count">
                <?php echo e($enrollments->count()); ?> Course<?php echo e($enrollments->count() === 1 ? '' : 's'); ?>

            </span>
        </div>

        <div class="student-table-wrap">
            <table class="student-enrollment-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Course Code</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="course-title">
                                <?php echo e($enrollment->course_name ?? '—'); ?>

                            </td>

                            <td>
                                <span class="course-code">
                                    <?php echo e($enrollment->course_code ?? '—'); ?>

                                </span>
                            </td>

                            <td><?php echo e($enrollment->semester_name ?? '—'); ?></td>
                            <td><?php echo e($enrollment->section_name ?? '—'); ?></td>

                            <td>
                                <span class="active-badge">
                                    <?php echo e(ucfirst($enrollment->status ?? 'active')); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="no-enrollment">
                                <strong>No enrollments found.</strong><br>
                                This student has not been added to a class section yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/admin/students/details.blade.php ENDPATH**/ ?>