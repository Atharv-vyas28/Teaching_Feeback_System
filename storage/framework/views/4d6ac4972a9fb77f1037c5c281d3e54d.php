<?php $__env->startSection('title', 'Submit Feedback'); ?>
<?php $header = 'Submit Feedback'; $subheader = 'Your response is completely anonymous.'; ?>

<?php $__env->startSection('sidebar-nav'); ?>
<div class="nav-section-label">Main</div>
<a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Courses</div>
<a href="<?php echo e(route('student.attendance')); ?>" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Attendance
</a>
<div class="nav-section-label">Feedback</div>
<a href="<?php echo e(route('student.feedback.index')); ?>" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    Submit Feedback
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;border-radius:14px;padding:18px 22px;margin-bottom:22px;display:flex;align-items:center;gap:16px;">
    <div style="width:44px;height:44px;background:linear-gradient(135deg,#3B82F6,#2563EB);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="22" height="22" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    </div>
    <div>
        <div style="font-weight:700;color:#0F172A;font-size:0.95rem;"><?php echo e($classSession->section->course->name ?? 'N/A'); ?></div>
        <div style="font-size:0.82rem;color:#64748B;margin-top:2px;">
            Faculty: <?php echo e($faculty->name ?? 'N/A'); ?> &nbsp;·&nbsp; Date: <?php echo e($classSession->session_date->format('M d, Y')); ?>

            <?php if($classSession->topic): ?> &nbsp;·&nbsp; <?php echo e($classSession->topic); ?> <?php endif; ?>
        </div>
        <div style="font-size:0.75rem;color:#3B82F6;margin-top:4px;font-weight:500;">🔒 Your feedback is completely anonymous — no personal data is linked to your responses.</div>
    </div>
</div>

<form method="POST" action="<?php echo e(route('student.feedback.submit', $feedbackSession)); ?>">
    <?php echo csrf_field(); ?>
    <div style="display:flex;flex-direction:column;gap:16px;">
        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card">
            <div class="card-body">
                <div style="font-weight:600;color:#0F172A;margin-bottom:14px;font-size:0.9rem;">
                    <?php echo e($loop->iteration); ?>. <?php echo e($q->question_text); ?>

                    <?php if($q->is_required): ?><span style="color:#DC2626;margin-left:4px;">*</span><?php endif; ?>
                </div>

                <?php if($q->type === 'rating'): ?>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <label style="cursor:pointer;">
                        <input type="radio"
                               name="answers[<?php echo e($q->id); ?>][rating]"
                               value="<?php echo e($i); ?>"
                               id="q<?php echo e($q->id); ?>_r<?php echo e($i); ?>"
                               style="display:none;"
                               class="star-radio"
                               data-qid="<?php echo e($q->id); ?>"
                               <?php echo e(old("answers.{$q->id}.rating") == $i ? 'checked' : ''); ?>>
                        <div class="star-btn" data-val="<?php echo e($i); ?>" data-qid="<?php echo e($q->id); ?>"
                             style="width:52px;height:52px;border-radius:12px;border:2px solid #E2E8F0;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:all 0.2s;background:#FAFBFC;gap:2px;">
                            <span style="font-size:1.2rem;"><?php echo e(['😞','😐','🙂','😊','🤩'][$i-1]); ?></span>
                            <span style="font-size:0.65rem;font-weight:600;color:#94A3B8;"><?php echo e($i); ?></span>
                        </div>
                    </label>
                    <?php endfor; ?>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:6px;padding:0 4px;">
                    <span style="font-size:0.72rem;color:#94A3B8;">Poor</span>
                    <span style="font-size:0.72rem;color:#94A3B8;">Excellent</span>
                </div>

                <?php elseif($q->type === 'text'): ?>
                <textarea name="answers[<?php echo e($q->id); ?>][text]"
                          class="form-textarea"
                          rows="3"
                          placeholder="Share your thoughts… (optional)"
                          maxlength="1000"><?php echo e(old("answers.{$q->id}.text")); ?></textarea>
                <div style="text-align:right;font-size:0.72rem;color:#94A3B8;margin-top:4px;">Max 1000 characters</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;">
        <button type="submit" class="btn-primary" style="padding:12px 28px;font-size:0.95rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Submit Feedback Anonymously
        </button>
        <a href="<?php echo e(route('student.feedback.index')); ?>" class="btn-secondary">Cancel</a>
    </div>
</form>

<?php $__env->startPush('styles'); ?>
<style>
.star-btn:hover, .star-btn.selected {
    border-color: #3B82F6 !important;
    background: #EFF6FF !important;
}
.star-btn.selected span:last-child { color: #1D4ED8 !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.star-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const qid = this.dataset.qid;
        const val = parseInt(this.value);
        document.querySelectorAll(`.star-btn[data-qid="${qid}"]`).forEach(btn => {
            btn.classList.toggle('selected', parseInt(btn.dataset.val) <= val);
        });
    });
    if (radio.checked) radio.dispatchEvent(new Event('change'));
});

document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const qid = this.dataset.qid;
        const val = this.dataset.val;
        const radio = document.querySelector(`#q${qid}_r${val}`);
        if (radio) { radio.checked = true; radio.dispatchEvent(new Event('change')); }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/student/feedback/show.blade.php ENDPATH**/ ?>