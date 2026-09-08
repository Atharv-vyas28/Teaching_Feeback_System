<?php $__env->startSection('title', 'My Attendance'); ?>
<?php $header = 'My Attendance'; $subheader = 'Detailed attendance record across all enrolled courses.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Courses</div>
<a href="<?php echo e(route('student.attendance')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Attendance
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('student.feedback.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Submit Feedback
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php if($courseSummary->isNotEmpty()): ?>
<div style="margin-bottom:20px;">
    <h4 style="font-size:0.8rem;color:#64748B;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;">Course-wise Summary</h4>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
        <?php $__currentLoopData = $courseSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $pct = $course->attendance_pct;
            $color = $pct >= 75 ? '#15803D' : ($pct >= 60 ? '#B45309' : '#B91C1C');
            $bg    = $pct >= 75 ? 'linear-gradient(135deg,#DCFCE7,#BBF7D0)' : ($pct >= 60 ? 'linear-gradient(135deg,#FEF9C3,#FDE68A)' : 'linear-gradient(135deg,#FEE2E2,#FECACA)');
        ?>
        <div style="background:white;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
            <div style="font-weight:700;color:#0F172A;font-size:0.88rem;margin-bottom:2px;"><?php echo e($course->course_name); ?></div>
            <div style="font-size:0.72rem;color:#94A3B8;margin-bottom:10px;"><?php echo e($course->course_code); ?></div>
            <div style="display:flex;gap:10px;margin-bottom:8px;">
                <div style="text-align:center;">
                    <div style="font-size:1.1rem;font-weight:700;color:#0F172A;"><?php echo e($course->total_classes); ?></div>
                    <div style="font-size:0.68rem;color:#94A3B8;">Total</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.1rem;font-weight:700;color:#15803D;"><?php echo e($course->present_count); ?></div>
                    <div style="font-size:0.68rem;color:#94A3B8;">Present</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.1rem;font-weight:700;color:#DC2626;"><?php echo e($course->absent_count); ?></div>
                    <div style="font-size:0.68rem;color:#94A3B8;">Absent</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.1rem;font-weight:700;color:<?php echo e($color); ?>;"><?php echo e($pct); ?>%</div>
                    <div style="font-size:0.68rem;color:#94A3B8;">Attend.</div>
                </div>
            </div>
            
            <div style="background:#F1F5F9;border-radius:4px;height:5px;overflow:hidden;">
                <div style="width:<?php echo e($pct); ?>%;height:100%;background:<?php echo e($color); ?>;border-radius:4px;transition:width 0.5s;"></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="card">
    <div class="card-header">
        <h3>Attendance Records</h3>
        <span style="font-size:0.78rem;color:#64748B;"><?php echo e($records->total()); ?> total records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Topic</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Source</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;"><?php echo e($rec->session->section->course->name ?? 'N/A'); ?></div>
                        <div style="font-size:0.75rem;color:#94A3B8;"><?php echo e($rec->session->section->course->code ?? ''); ?></div>
                    </td>
                    <td><?php echo e($rec->session->topic ?? '—'); ?></td>
                    <td><?php echo e($rec->session->session_date?->format('M d, Y') ?? 'N/A'); ?></td>
                    <td>
                        <span class="badge <?php echo e(['present'=>'badge-green','absent'=>'badge-red','late'=>'badge-yellow','excused'=>'badge-blue'][$rec->status] ?? 'badge-gray'); ?>">
                            <?php echo e(ucfirst($rec->status)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if(($rec->source ?? 'regular') === 'feedback'): ?>
                            <span class="badge badge-blue" title="Marked present via feedback submission">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Feedback
                            </span>
                        <?php else: ?>
                            <span class="badge badge-gray">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Regular
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">No attendance records yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($records->hasPages()): ?>
    <div style="padding:16px 20px;"><?php echo e($records->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/student/attendance.blade.php ENDPATH**/ ?>