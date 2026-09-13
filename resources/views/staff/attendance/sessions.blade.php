@extends('layouts.dashboard')
@section('title', 'My Sessions')
@php $header = 'Class Sessions'; $subheader = 'All sessions for your assigned sections.'; @endphp

@section('sidebar-nav')
@include('staff.partials.sidebar')
<!--
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    Courses
</a>
<a href="{{ route('staff.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
-->
@endsection

@section('page-actions')
<a href="{{ route('staff.attendance.create') }}" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Lecture
</a>
@endsection

@section('content')
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>Course / Section</th><th>Topic</th><th>Date</th><th>Time</th><th>Attendance</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $session->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">Section {{ $session->section->section_name ?? '' }}</div>
                    </td>
                    <td>{{ $session->topic ?? '—' }}</td>
                    <td>{{ $session->session_date->format('M d, Y') }}</td>
                    <td style="font-size:0.8rem;color:#64748B;">{{ substr($session->start_time, 0, -3) }} – {{ substr($session->end_time, 0, -3) }}</td>
                    <td>
                        <span class="badge badge-blue">
                            {{ $session->attendanceRecords->whereIn('status',['present','late'])->count() }} / {{ $session->attendanceRecords->count() }}
                        </span>
                    </td>
                    <td><span class="badge {{ ['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray' }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if(in_array($session->status, ['ongoing','scheduled']))
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-primary btn-sm">Take Attendance</a>
                        @else
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-secondary btn-sm">View</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:#94A3B8;">No sessions yet. <a href="{{ route('staff.attendance.create') }}" style="color:#3B82F6;">Create one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sessions->hasPages())
    <div style="padding:16px 20px;">{{ $sessions->links() }}</div>
    @endif
</div>
@endsection
