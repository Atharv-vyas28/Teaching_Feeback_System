@extends('layouts.dashboard')

@section('title', 'Staff Assignments')
@php
    $header = 'Staff Assignments';
    $subheader = 'Assign Staff to a course section and, when needed, a specific feedback session.';
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    @if (session('success'))
        <div class="alert-success" style="margin-bottom:16px;">
            {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>
    @endif


    <div class="card">
        <div class="card-header">
            <h3>Assignment History</h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Course / Section</th>
                        <th>Feedback Session</th>
                        <th>Assigned</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td><strong>{{ $assignment->staff->name }}</strong><br><small>{{ $assignment->staff->employee_id }}</small>
                            </td>
                            <td>{{ $assignment->section->course->code }} —
                                {{ $assignment->section->course->name }}<br><small>{{ $assignment->section->course->department?->name }}
                                    · Sec {{ $assignment->section->section_name }}</small></td>
                            <td>{{ $assignment->feedbackSession ? '#' . $assignment->feedbackSession->id : 'Course access only' }}
                            </td>
                            <td>{{ $assignment->assigned_at?->format('d M Y, h:i A') ?? $assignment->created_at->format('d M Y') }}<br><small>by
                                    {{ $assignment->assignedBy?->name ?? 'Admin' }}</small></td>
                            <td><span
                                    class="badge {{ $assignment->is_active ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($assignment->status) }}</span>
                            </td>
                            <td>
                                @if ($assignment->is_active)
                                    <form method="POST"
                                        action="{{ route('admin.staff-assignments.deactivate', $assignment) }}"
                                        onsubmit="return confirm('Deactivate this assignment? Staff access will end immediately.')">
                                        @csrf<button class="btn-secondary btn-sm" type="submit">Deactivate</button></form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty <tr>
                            <td colspan="6" style="text-align:center;padding:35px;color:#94A3B8;">No Staff assignments
                                have been created.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $assignments->links() }}</div>
    </div>
@endsection
