<?php $__env->startSection('title', isset($feedbackSession) ? 'Feedback-Day Attendance' : 'Take Attendance'); ?>

<?php
    $feedbackClassSession = isset($feedbackSession)
        ? $feedbackSession->classSession
        : $classSession;

    $section = $feedbackClassSession?->section;
    $course = $section?->course;

    $header = isset($feedbackSession)
        ? 'Feedback-Day Attendance'
        : 'Take Attendance';

    $subheader = $course?->name ?? 'Class Session';
?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('staff.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!--
    <div class="nav-section-label">Main</div>
    <a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1-1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>

    <div class="nav-section-label">Attendance</div>

    <a href="<?php echo e(route('staff.attendance.sessions')); ?>" class="nav-link active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        My Sessions
    </a>

    <a href="<?php echo e(route('staff.attendance.create')); ?>" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v16m8-8H4"/>
        </svg>
        New Session
    </a>
    -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    .attendance-hero {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
    }

    .attendance-hero-top,
    .attendance-toolbar,
    .attendance-actions {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }

    .session-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        margin-bottom: 10px;
        border: 1px solid #dbeafe;
        border-radius: 20px;
        background: #eff6ff;
        color: #2563eb;
        font-size: .72rem;
        font-weight: 700;
    }

    .attendance-hero h2 {
        margin: 0;
        color: #0f172a;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .attendance-hero p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: .85rem;
    }

    .session-label {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        color: #64748b;
        font-size: .75rem;
    }

    .session-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #e2e8f0;
    }

    .session-detail-card {
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
    }

    .session-detail-label {
        margin-bottom: 6px;
        color: #94a3b8;
        font-size: .65rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .session-detail-value {
        color: #334155;
        font-size: .85rem;
        font-weight: 650;
    }

    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 3px 10px rgba(15, 23, 42, .03);
    }

    .summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .summary-total .summary-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .summary-present .summary-icon {
        background: #ecfdf5;
        color: #16a34a;
    }

    .summary-absent .summary-icon {
        background: #fef2f2;
        color: #dc2626;
    }

    .summary-label {
        margin-bottom: 3px;
        color: #64748b;
        font-size: .72rem;
    }

    .summary-number {
        color: #0f172a;
        font-size: 1.3rem;
        font-weight: 750;
    }

    .attendance-table {
        width: 100%;
    }

    .attendance-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: .7rem;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .attendance-table tbody tr:hover {
        background: #f8fafc;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        font-size: .75rem;
        font-weight: 750;
        flex-shrink: 0;
    }

    .staff-status-select {
        min-width: 125px;
        padding: 8px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: .78rem;
        font-weight: 650;
        outline: none;
    }

    .student-search {
        position: relative;
        flex: 1 1 250px;
        max-width: 330px;
    }

    .student-search svg {
        position: absolute;
        top: 50%;
        left: 12px;
        width: 16px;
        height: 16px;
        color: #94a3b8;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .student-search input {
        width: 100%;
        box-sizing: border-box;
        padding: 9px 12px 9px 38px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #0f172a;
        font-size: .8rem;
        outline: none;
    }

    .student-search input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }

    .attendance-bulk-actions,
    .action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .attendance-actions {
        align-items: center;
        padding: 18px 20px;
        border-top: 1px solid #e2e8f0;
    }

    .attendance-toggle-btn {
        min-width: 105px;
        padding: 8px 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: .78rem;
        font-weight: 650;
        transition: all .2s ease;
    }

    .attendance-toggle-btn:hover {
        transform: translateY(-1px);
    }

    .attendance-toggle-btn.present {
        background: #dcfce7;
        color: #15803d;
    }

    .attendance-toggle-btn.absent {
        background: #fee2e2;
        color: #dc2626;
    }

    @media (max-width: 700px) {
        .attendance-summary {
            grid-template-columns: 1fr;
        }

        .attendance-hero {
            padding: 18px;
        }

        .session-details {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        .student-search {
            max-width: none;
            flex-basis: 100%;
        }

        .attendance-bulk-actions,
        .action-buttons {
            width: 100%;
        }

        .attendance-bulk-actions button,
        .action-buttons > * {
            flex: 1;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .session-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="attendance-hero">
    <div class="attendance-hero-top">
        <div>
            <div class="session-badge">
                <?php echo e(isset($feedbackSession) ? 'Feedback-Day Attendance' : 'Staff Attendance Session'); ?>

            </div>

            <h2>
                <?php echo e($course?->name ?? 'Class Session'); ?>

            </h2>

            <p>
                <?php echo e(isset($feedbackSession)
                    ? 'Mark attendance for students participating in the feedback session.'
                    : ($feedbackClassSession->topic ?? 'No topic specified for this session.')); ?>

            </p>
        </div>

        <div class="session-label">
            <?php echo e(isset($feedbackSession) ? 'Feedback Attendance' : 'Staff Marking Access'); ?>

        </div>
    </div>

    <div class="session-details">

        <div class="session-detail-card">
            <div class="session-detail-label">Course</div>
            <div class="session-detail-value">
                <?php echo e($course?->name ?? 'N/A'); ?>

            </div>
        </div>

        <div class="session-detail-card">
            <div class="session-detail-label">Section</div>
            <div class="session-detail-value">
                <?php echo e($section?->section_name ?? 'N/A'); ?>

            </div>
        </div>

        <div class="session-detail-card">
            <div class="session-detail-label">Date</div>
            <div class="session-detail-value">
                <?php echo e(isset($feedbackSession)
                    ? ($feedbackClassSession->session_date?->format('M d, Y') ?? 'N/A')
                    : now()->format('M d, Y')); ?>

            </div>
        </div>

        <div class="session-detail-card">
            <div class="session-detail-label">Time</div>
            <div class="session-detail-value" style="padding:0 10px;">
                -
            </div>
        </div>

        <div class="session-detail-card">
            <div class="session-detail-label">Type</div>
            <div class="session-detail-value">
                Feedback
            </div>
        </div>

    </div>
</div>

<div class="attendance-summary">

    <div class="summary-card summary-total">
        <div class="summary-icon">👥</div>

        <div>
            <div class="summary-label">Total Students</div>
            <div class="summary-number">
                <?php echo e(count($students)); ?>

            </div>
        </div>
    </div>

    <div class="summary-card summary-present">
        <div class="summary-icon">✓</div>

        <div>
            <div class="summary-label">Present / Late</div>
            <div class="summary-number" id="presentCount">0</div>
        </div>
    </div>

    <div class="summary-card summary-absent">
        <div class="summary-icon">✕</div>

        <div>
            <div class="summary-label">Absent / Excused</div>
            <div class="summary-number" id="absentCount">0</div>
        </div>
    </div>

</div>

<div class="card">

    <div class="card-header">

        <div>
            <h3 style="margin-bottom:4px;">
                Mark Attendance
            </h3>

            <div style="font-size:.78rem;color:#64748b;">
                Select Present or Absent for each student.
            </div>
        </div>

        <div class="attendance-toolbar">

            <label class="student-search">

                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"
                    />
                </svg>

                <input
                    type="search"
                    placeholder="Search name or roll number..."
                    oninput="filterStudents(this.value)"
                    autocomplete="off"
                >

            </label>

            <div class="attendance-bulk-actions">

                <button
                    type="button"
                    onclick="markAll('present')"
                    class="btn-success btn-sm"
                >
                    ✓ Mark All Present
                </button>

                <button
                    type="button"
                    onclick="markAll('absent')"
                    class="btn-secondary btn-sm"
                >
                    ✕ Mark All Absent
                </button>

            </div>

        </div>

    </div>

    <form
        method="POST"
        action="<?php echo e(isset($feedbackSession)
            ? route('staff.feedback.attendance.save', $feedbackSession)
            : route('staff.attendance.save', $classSession)); ?>"
    >
        <?php echo csrf_field(); ?>

        <div style="overflow-x:auto;">

            <table class="data-table attendance-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Roll Number</th>
                        <th>Attendance</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $existing = $existingAttendance[$student->id] ?? null;

                            $currentStatus = in_array(
                                $existing?->status,
                                ['present', 'late']
                            )
                                ? 'present'
                                : 'absent';
                        ?>

                        <tr
                            class="student-row"
                            data-search="<?php echo e(strtolower(
                                $student->name . ' ' . ($student->roll_number ?? '')
                            )); ?>"
                        >

                            <td style="color:#94a3b8;font-weight:650;">
                                <?php echo e($i + 1); ?>

                            </td>

                            <td>

                                <div class="student-info">

                                    <div class="student-avatar">
                                        <?php echo e(strtoupper(substr($student->name, 0, 1))); ?>

                                    </div>

                                    <div>

                                        <div style="font-weight:650;color:#0f172a;">
                                            <?php echo e($student->name); ?>

                                        </div>

                                        <div style="font-size:.7rem;color:#94a3b8;margin-top:2px;">
                                            Student
                                        </div>

                                    </div>

                                </div>

                            </td>

                            <td style="color:#64748b;font-size:.82rem;">
                                <?php echo e($student->roll_number ?? '—'); ?>

                            </td>

                            <td>

                                <input
                                    type="hidden"
                                    name="attendance[<?php echo e($student->id); ?>][status]"
                                    value="<?php echo e($currentStatus); ?>"
                                    class="status-input"
                                    id="status-<?php echo e($student->id); ?>"
                                >

                                <button
                                    type="button"
                                    id="attendance-btn-<?php echo e($student->id); ?>"
                                    class="attendance-toggle-btn <?php echo e($currentStatus); ?>"
                                    onclick="toggleAttendance(<?php echo e($student->id); ?>)"
                                >
                                    <?php echo e($currentStatus === 'present'
                                        ? '✓ Present'
                                        : '✕ Absent'); ?>

                                </button>

                            </td>

                            <td>

                                <input
                                    type="text"
                                    name="attendance[<?php echo e($student->id); ?>][remarks]"
                                    class="form-input"
                                    style="width:170px;"
                                    value="<?php echo e($existing?->remarks ?? ''); ?>"
                                    placeholder="Optional remark"
                                >

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr id="studentSearchEmptyRow" hidden>
                        <td
                            colspan="5"
                            style="padding:32px;text-align:center;color:#94a3b8;"
                        >
                            No student matches your search.
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

        <div class="attendance-actions">

            <div style="font-size:.78rem;color:#64748b;">
                Review the attendance before saving.
            </div>

            <div class="action-buttons">

                <a
                    href="<?php echo e(isset($feedbackSession)
                        ? route('staff.feedback.index')
                        : route('staff.attendance.sessions')); ?>"
                    class="btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    <?php echo e(isset($feedbackSession)
                        ? 'Save Feedback Attendance'
                        : 'Save Attendance'); ?>

                </button>

            </div>

        </div>

    </form>

</div>

<?php $__env->startPush('scripts'); ?>

<script>
    function filterStudents(query) {
        const searchTerm = query.trim().toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        const emptyRow = document.getElementById('studentSearchEmptyRow');

        let visibleRows = 0;

        rows.forEach(function (row) {
            const matches = (row.dataset.search || '').includes(searchTerm);

            row.hidden = !matches;

            if (matches) {
                visibleRows++;
            }
        });

        if (emptyRow) {
            emptyRow.hidden = visibleRows > 0;
        }
    }

    function updateButton(studentId, status) {
        const input = document.getElementById(`status-${studentId}`);
        const button = document.getElementById(`attendance-btn-${studentId}`);

        if (!input || !button) {
            return;
        }

        input.value = status;

        button.classList.remove('present', 'absent');
        button.classList.add(status);

        button.textContent = status === 'present'
            ? '✓ Present'
            : '✕ Absent';
    }

    function toggleAttendance(studentId) {
        const input = document.getElementById(`status-${studentId}`);

        if (!input) {
            return;
        }

        updateButton(
            studentId,
            input.value === 'present'
                ? 'absent'
                : 'present'
        );

        updateCounts();
    }

    function markAll(status) {
        document.querySelectorAll('.status-input').forEach(function (input) {
            const studentId = input.id.replace('status-', '');

            updateButton(studentId, status);
        });

        updateCounts();
    }

    function updateCounts() {
        let present = 0;
        let absent = 0;

        document.querySelectorAll('.status-input').forEach(function (input) {
            if (input.value === 'present') {
                present++;
            } else {
                absent++;
            }
        });

        document.getElementById('presentCount').textContent = present;
        document.getElementById('absentCount').textContent = absent;
    }

    document.addEventListener('DOMContentLoaded', updateCounts);
</script>

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/staff/attendance/take.blade.php ENDPATH**/ ?>