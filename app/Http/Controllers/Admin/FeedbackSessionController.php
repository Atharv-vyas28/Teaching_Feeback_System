<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\StaffCourse;
use App\Models\User;
use App\Services\FeedbackRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackSessionController extends Controller
{
    public function __construct(private FeedbackRatingService $ratingService) {}

    public function index()
    {
        $sessions = FeedbackSession::with(['classSession.section.course', 'classSession.section.faculty', 'assignedStaff'])
            ->orderByDesc('created_at')->paginate(20);
        $staffMembers = User::where('role', 'staff')->where('is_active', true)->orderBy('name')->get();
        return view('admin.feedback-sessions.index', compact('sessions', 'staffMembers'));
    }

    /**
     * Show form to create a new feedback session from Admin panel.
     */
    public function create()
    {
        // Load class sessions that do NOT already have a feedback session
        $classSessions = ClassSession::with(['section.course.department'])
            ->whereDoesntHave('feedbackSession')
            ->orderByDesc('session_date')
            ->get();

        $staffMembers = User::where('role', 'staff')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.feedback-sessions.create', compact('classSessions', 'staffMembers'));
    }

    /**
     * Store a new feedback session from Admin panel.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_session_id' => ['required', 'exists:class_sessions,id', 'unique:feedback_sessions,class_session_id'],
            'staff_id'         => ['nullable', 'exists:users,id'],
            'release_at'       => ['nullable', 'date'],
            'deadline_at'      => ['nullable', 'date', 'after:release_at'],
        ]);

        $staffId = null;
        if (! empty($data['staff_id'])) {
            $staff = User::findOrFail($data['staff_id']);
            abort_unless($staff->isStaff(), 422, 'Selected user is not a staff member.');
            $staffId = $staff->id;
        }

        $classSession = ClassSession::findOrFail($data['class_session_id']);

        $status = 'draft';
        if (! empty($data['release_at']) && now()->greaterThanOrEqualTo($data['release_at'])) {
            $status = 'active';
        }

        FeedbackSession::create([
            'class_session_id'  => $data['class_session_id'],
            'created_by'        => auth()->id(),
            'assigned_staff_id' => $staffId,
            'status'            => $status,
            'release_at'        => $data['release_at'] ?? null,
            'deadline_at'       => $data['deadline_at'] ?? null,
            'opened_at'         => $status === 'active' ? now() : null,
            'is_released'       => false,
        ]);

        return redirect()
            ->route('admin.feedback-sessions.index')
            ->with('success', 'Feedback session created successfully.');
    }

    public function assignStaff(Request $request, FeedbackSession $session)
    {
        $data = $request->validate(['staff_id' => ['required', 'exists:users,id']]);
        $staff = User::findOrFail($data['staff_id']);
        abort_unless($staff->isStaff(), 422, 'Selected user is not a staff member.');

        $isAssigned = StaffCourse::where('user_id', $staff->id)
            ->where('class_section_id', $session->classSession->class_section_id)
            ->where('is_active', true)->exists();

        if (! $isAssigned) {
            return back()->with('error', 'Assign this Staff member to the course section first.');
        }

        $session->update(['assigned_staff_id' => $staff->id]);
        return back()->with('success', 'Staff assigned to this feedback session.');
    }

    public function responses(FeedbackSession $session)
    {
        $session->load(['classSession.section.course', 'classSession.section.faculty']);
        $responses = $session->responses()->with('answers.question')->orderBy('submitted_at')->get();
        $responseTimeline = $responses->filter(fn ($r) => $r->submitted_at)
            ->groupBy(fn ($r) => $r->submitted_at->format('Y-m-d H:00'))
            ->map(fn ($items, $period) => ['period' => $period, 'label' => \Carbon\Carbon::parse($period)->format('M d, H:00'), 'count' => $items->count()])->values();
        $questionAnalysis = [];
        $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($responses as $response) foreach ($response->answers as $answer) {
            if ($answer->question?->type !== 'rating' || $answer->rating_value === null) continue;
            $id = $answer->feedback_question_id;
            $questionAnalysis[$id]['question'] ??= $answer->question->question_text;
            $questionAnalysis[$id]['ratings'][] = (float) $answer->rating_value;
            $rating = (int) round($answer->rating_value);
            if (isset($ratingDistribution[$rating])) $ratingDistribution[$rating]++;
        }
        $questionAnalysis = collect($questionAnalysis)->map(fn ($item) => [
            'question' => $item['question'],
            'average' => round(array_sum($item['ratings']) / count($item['ratings']), 2),
            'response_count' => count($item['ratings']),
        ])->values();
        return view('admin.feedback-sessions.responses', compact('session', 'responses', 'responseTimeline', 'questionAnalysis', 'ratingDistribution'));
    }

    public function release(Request $request, FeedbackSession $session)
    {
        if ($session->isReleased()) return back()->with('success', 'This feedback was already released to faculty.');
        $responseCount = $session->responses()->count();
        if ($responseCount === 0) return back()->with('error', 'No student feedback has been submitted for this session yet.');
        DB::transaction(function () use ($session) {
            $session->update(['status' => 'closed', 'closed_at' => now()]);
            $this->ratingService->calculateForSession($session->fresh());
            $session->update(['is_released' => true]);
        });
        return back()->with('success', "{$responseCount} anonymous response(s) released to faculty.");
    }
}
