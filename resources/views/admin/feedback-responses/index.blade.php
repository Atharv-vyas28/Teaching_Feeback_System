
@extends('layouts.dashboard')

@section('title', 'Pending Feedback')
@php
    $header = 'Pending Feedback';
    $subheader = 'Review anonymous student feedback';
@endphp

@section('sidebar-nav')
    @include('admin.partials.nav')
@endsection

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.feedback-responses.approve', ['sessionId' => $sessionId]) }}">
        @csrf
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-slate-800">Pending Feedback (Session #{{ $sessionId }})</h2>
            <button type="submit" class="btn-primary">Approve Selected</button>
        </div>
        <div class="overflow-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Submitted At</th>
                        <th>Answers</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($responses as $resp)
                    <tr>
                        <td><input type="checkbox" name="response_ids[]" value="{{ $resp->id }}"></td>
                        <td>{{ $resp->submitted_at->format('M d, Y H:i') }}</td>
                        <td>
                            <ul class="list-disc pl-5">
                                @foreach($resp->answers as $ans)
                                    <li>
                                        <strong>{{ $ans->question->question_text }}:</strong>
                                        @if($ans->question->type === 'rating')
                                            {{ $ans->rating_value ?? 'N/A' }}
                                        @else
                                            {{ $ans->text_answer ?? 'N/A' }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-slate-400 py-6">No pending feedback.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('select-all')?.addEventListener('change', function(e){
    document.querySelectorAll('input[name="response_ids[]"]').forEach(cb => cb.checked = e.target.checked);
});
</script>
@endpush
@endsection

