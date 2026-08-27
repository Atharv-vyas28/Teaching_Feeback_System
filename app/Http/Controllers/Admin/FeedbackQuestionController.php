<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackQuestion;
use Illuminate\Http\Request;

class FeedbackQuestionController extends Controller
{
    public function index()
    {
        $questions = FeedbackQuestion::orderBy('display_order')->get();
        $totalWeight = $questions->where('is_active', true)->where('type', 'rating')->sum('weight');
        return view('admin.feedback-questions.index', compact('questions', 'totalWeight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type'          => 'required|in:rating,text,multiple_choice',
            'max_marks'     => 'required|numeric|min:1|max:10',
            'weight'        => 'required|numeric|min:0|max:100',
            'display_order' => 'required|integer|min:0',
            'is_required'   => 'boolean',
        ]);
        FeedbackQuestion::create($request->only(
            'question_text', 'type', 'max_marks', 'weight', 'display_order', 'is_required'
        ) + ['is_active' => true]);
        return back()->with('success', 'Question added.');
    }

    public function update(Request $request, FeedbackQuestion $feedbackQuestion)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type'          => 'required|in:rating,text,multiple_choice',
            'max_marks'     => 'required|numeric|min:1|max:10',
            'weight'        => 'required|numeric|min:0|max:100',
            'display_order' => 'required|integer|min:0',
            'is_active'     => 'boolean',
            'is_required'   => 'boolean',
        ]);
        $feedbackQuestion->update($request->only(
            'question_text', 'type', 'max_marks', 'weight', 'display_order'
        ) + [
            'is_active'   => $request->boolean('is_active'),
            'is_required' => $request->boolean('is_required'),
        ]);
        return back()->with('success', 'Question updated.');
    }

    public function destroy(FeedbackQuestion $feedbackQuestion)
    {
        $feedbackQuestion->delete();
        return back()->with('success', 'Question deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['questions' => 'required|array']);
        foreach ($request->questions as $index => $id) {
            FeedbackQuestion::where('id', $id)->update(['display_order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }

    public function toggle(FeedbackQuestion $feedbackQuestion)
    {
        $feedbackQuestion->update(['is_active' => !$feedbackQuestion->is_active]);
        return back()->with('success', 'Question status toggled.');
    }
}
