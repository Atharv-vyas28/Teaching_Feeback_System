@extends('layouts.dashboard')
@section('title', 'Feedback Responses')
@php $header = 'Feedback Responses'; $subheader = 'Review anonymous student feedback for this session.'; @endphp

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Responses for {{ $session->classSession->section->course->name ?? 'Course' }} – {{ $session->classSession->topic ?? '' }}</h3>
    </div>
    <div class="card-body">
        @if($session->responses->isEmpty())
            <div class="empty-state">
                <p>No feedback has been submitted for this session yet.</p>
            </div>
        @else
            @foreach($session->responses as $response)
                <div class="stat-card" style="margin-bottom:20px;">
                    <div style="font-size:0.85rem;color:#64748B;">Response #{{ $loop->iteration }} – Anonymous</div>
                    <table class="data-table" style="margin-top:8px;">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($response->answers as $answer)
                                <tr>
                                    <td>{{ $answer->question->question_text }}</td>
                                    <td>
                                        @if($answer->question->type === 'rating')
                                            {{ $answer->rating_value ?? '—' }} / {{ $answer->question->max_marks }}
                                        @else
                                            {{ $answer->text_answer ?? '—' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            <form method="POST" action="{{ route('admin.feedback-sessions.release', $session) }}" style="margin-top:20px;">
                @csrf
                <button type="submit" class="btn-primary">Release Feedback to Faculty</button>
            </form>
        @endif
    </div>
</div>
@endsection
