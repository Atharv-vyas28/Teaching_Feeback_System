@extends('layouts.dashboard')

@section('title', 'Feedback Sessions Overview')

@php
    $header = 'Feedback Sessions';
    $subheader = 'Review anonymous feedback and release it to faculty.';
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Feedback Sessions</h3>
    </div>

    @if(session('success'))
        <div style="background:#DCFCE7;color:#166534;padding:12px 16px;border-radius:8px;margin:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#FEE2E2;color:#991B1B;padding:12px 16px;border-radius:8px;margin:16px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course & Topic</th>
                    <th>Assigned Faculty</th>
                    <th>Feedback Staff</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Release Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sessions as $session)
                    @php
                        $faculty = $session->classSession?->section?->faculty?->first();
                    @endphp

                    <tr>
                        <td>
                            <div style="font-weight:600;">
                                {{ $session->classSession?->section?->course?->name ?? 'Unknown course' }}
                            </div>

                            <div style="font-size:0.8rem;color:#64748B;">
                                {{ $session->classSession?->topic ?? 'No topic' }}
                            </div>
                        </td>

                        <td>
                            {{ $faculty?->name ?? 'Unassigned' }}
                        </td>

                        <td>
                            <form method="POST" action="{{ route('admin.feedback-sessions.assign-staff', $session) }}" style="display:flex;gap:6px;align-items:center;">
                                @csrf
                                <select name="staff_id" class="form-select" style="min-width:145px;" required>
                                    <option value="">Assign staff...</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" @selected($session->assigned_staff_id === $staff->id)>{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-secondary btn-sm">Save</button>
                            </form>
                            @if($session->assignedStaff)
                                <div style="font-size:.72rem;color:#059669;margin-top:5px;">Assigned: {{ $session->assignedStaff->name }}</div>
                            @endif
                        </td>

                        <td>
                            {{ $session->classSession?->session_date?->format('M d, Y') ?? 'N/A' }}
                        </td>

                        <td>
                            <span class="badge badge-{{ [
                                'draft' => 'gray',
                                'active' => 'green',
                                'closed' => 'blue'
                            ][$session->status] ?? 'gray' }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>

                        <td>
                            @if($session->isReleased())
                                <span class="badge badge-green">
                                    Released
                                </span>
                            @else
                                <form
                                    method="POST"
                                    action="{{ route('admin.feedback-sessions.release', $session) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Release anonymous feedback to the assigned faculty? This will close the feedback session.')"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="badge badge-yellow"
                                        style="border:0;cursor:pointer;"
                                        title="Release anonymous feedback to faculty"
                                    >
                                        Not Released · Click to Release
                                    </button>
                                </form>
                            @endif
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.feedback-sessions.responses', $session) }}"
                                class="btn-secondary btn-sm"
                            >
                                View Feedback
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:24px;">
                            No feedback sessions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sessions->hasPages())
        <div style="margin:20px;">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
@endsection
