<?php $__env->startSection('title', 'Feedback Analytics'); ?>
<?php $header = 'Feedback Analytics'; $subheader = 'Anonymous aggregated feedback for this session.'; ?>

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
<a href="<?php echo e(route('faculty.feedback.index')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Feedback Sessions
</a>
<a href="<?php echo e(route('faculty.feedback.my-ratings')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    My Ratings
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#1D4ED8;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-label">Eligible</div>
        <div class="stat-value"><?php echo e($eligibleCount); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#DCFCE7,#BBF7D0);color:#15803D;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Responses</div>
        <div class="stat-value"><?php echo e($responseCount); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#FEF9C3,#FDE68A);color:#92400E;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div class="stat-label">Overall Rating</div>
        <div class="stat-value"><?php echo e($ratingResult ? number_format($ratingResult->overall_weighted_rating, 1) : '—'); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#EDE9FE,#DDD6FE);color:#6D28D9;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="stat-label">Response Rate</div>
        <div class="stat-value"><?php echo e($eligibleCount > 0 ? round(($responseCount/$eligibleCount)*100) : 0); ?>%</div>
    </div>
</div>


<div class="card">
    <div class="card-header"><h3>Question-wise Analytics</h3><span style="font-size:0.78rem;color:#94A3B8;">Individual responses are never shown — anonymity is preserved</span></div>
    <div class="card-body">
        <?php if(count($questionStats) === 0): ?>
        <div style="text-align:center;padding:40px;color:#94A3B8;">No responses collected yet.</div>
        <?php else: ?>
        <?php $__currentLoopData = $questionStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #F1F5F9;">
            <div style="font-size:0.88rem;font-weight:600;color:#0F172A;margin-bottom:10px;"><?php echo e($stat['question']); ?></div>
            <?php if($stat['type'] === 'rating' && $stat['average'] !== null): ?>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="font-size:1.4rem;font-weight:800;color:<?php echo e($stat['average'] >= 4 ? '#1D4ED8' : ($stat['average'] >= 3 ? '#D97706' : '#DC2626')); ?>;">
                    <?php echo e(number_format($stat['average'], 1)); ?><span style="font-size:0.75rem;color:#94A3B8;">/5</span>
                </div>
                <div style="flex:1;background:#F1F5F9;border-radius:100px;height:8px;overflow:hidden;">
                    <div style="height:100%;width:<?php echo e(($stat['average']/5)*100); ?>%;background:linear-gradient(135deg,#3B82F6,#2563EB);border-radius:100px;transition:width 0.6s ease;"></div>
                </div>
                <span style="font-size:0.78rem;color:#94A3B8;"><?php echo e(count($stat['values'])); ?> responses</span>
            </div>
            <?php elseif($stat['type'] === 'text' && count($stat['texts']) > 0): ?>
            <div style="background:#F8FAFC;border-radius:10px;padding:12px;">
                <?php $__currentLoopData = $stat['texts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="font-size:0.85rem;color:#374151;padding:6px 0;border-bottom:1px solid #F1F5F9;">"<?php echo e($text); ?>"</div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div style="color:#94A3B8;font-size:0.82rem;">No data</div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/faculty/feedback/analytics.blade.php ENDPATH**/ ?>