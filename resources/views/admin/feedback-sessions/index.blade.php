@extends('layouts.dashboard')
@section('title', 'Feedback Sessions Overview')
@php $header = 'Feedback Sessions'; $subheader = 'Manage and release feedback to faculty.'; @endphp

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Feedback Sessions</h3>
    </div>
    
    @if(session('success'))
    <div style="background:#DCFCE7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course & Topic</th>
                    <th>Faculty</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Released?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $session->classSession->section->course->name ?? 'Unknown' }}</div>
                        <div style="font-size:0.8rem;color:#64748B;">{{ $session->classSession->topic ?? '' }}</div>
                    </td>
                    <td>{{ $session->classSession->faculty->name ?? 'Unknown' }}</td>
                    <td>{{ $session->classSession->session_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ ['draft'=>'gray','active'=>'green','closed'=>'blue'][$session->status] ?? 'gray' }}">
                            {{ ucfirst($session->status) }}
                        </span>
                    </td>
                    <td>
                        @if($session->is_released)
                            <span class="badge badge-green">Released</span>
                        @else
                            <span class="badge badge-yellow">Not Released</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.feedback-sessions.responses', $session) }}" class="btn-secondary btn-sm">View Feedback</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:20px;">No feedback sessions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top:20px;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
