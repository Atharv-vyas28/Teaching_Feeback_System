<?php $__env->startSection('title', 'My Ratings'); ?>
<?php $header = 'My Performance Ratings'; $subheader = 'Aggregated student feedback ratings for your courses.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('faculty.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="<?php echo e(route('faculty.attendance.sessions')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('faculty.feedback.index')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Feedback Sessions
</a>
<a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    My Ratings
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if($ratingResults->isEmpty()): ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px;color:#94A3B8;">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 16px;opacity:0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        <p>No ratings calculated yet. Ratings are computed when you close a feedback session.</p>
    </div>
</div>
<?php else: ?>
<div style="display:grid;gap:16px;">
    <?php $__currentLoopData = $ratingResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
                <div>
                    <div style="font-size:1rem;font-weight:700;color:#0F172A;"><?php echo e($r->section->course->name ?? 'N/A'); ?></div>
                    <div style="font-size:0.8rem;color:#64748B;margin-top:2px;"><?php echo e($r->semester->name ?? 'N/A'); ?> · <?php echo e($r->response_count); ?> responses</div>
                    <div style="font-size:0.75rem;color:#94A3B8;margin-top:2px;">Calculated: <?php echo e($r->calculated_at ? \Carbon\Carbon::parse($r->calculated_at)->format('M d, Y H:i') : 'N/A'); ?></div>
                </div>
                <div style="text-align:right;">
                    <?php if($r->overall_weighted_rating): ?>
                    <div style="font-size:2.4rem;font-weight:900;color:<?php echo e($r->overall_weighted_rating >= 4 ? '#1D4ED8' : ($r->overall_weighted_rating >= 3 ? '#D97706' : '#DC2626')); ?>;letter-spacing:-0.03em;line-height:1;">
                        <?php echo e(number_format($r->overall_weighted_rating, 1)); ?>

                    </div>
                    <div style="font-size:0.75rem;color:#94A3B8;">out of 5.0</div>
                    <?php $status = $r->overall_weighted_rating >= 4 ? ['Excellent','badge-green'] : ($r->overall_weighted_rating >= 3 ? ['Stable','badge-yellow'] : ['Needs Improvement','badge-red']); ?>
                    <span class="badge <?php echo e($status[1]); ?>" style="margin-top:6px;"><?php echo e($status[0]); ?></span>
                    <?php else: ?>
                    <span style="color:#94A3B8;">Pending</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if($r->question_averages && is_array(json_decode($r->question_averages, true))): ?>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #F1F5F9;">
                <?php $__currentLoopData = json_decode($r->question_averages, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                    <div style="font-size:0.8rem;color:#374151;flex:1;"><?php echo e($qa['question'] ?? 'Question'); ?></div>
                    <div style="width:120px;background:#F1F5F9;border-radius:100px;height:6px;overflow:hidden;">
                        <div style="height:100%;width:<?php echo e((($qa['average'] ?? 0)/5)*100); ?>%;background:linear-gradient(135deg,#3B82F6,#2563EB);border-radius:100px;"></div>
                    </div>
                    <span style="font-size:0.82rem;font-weight:700;color:#0F172A;min-width:30px;text-align:right;"><?php echo e(number_format($qa['average'] ?? 0, 1)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/faculty/feedback/my-ratings.blade.php ENDPATH**/ ?>