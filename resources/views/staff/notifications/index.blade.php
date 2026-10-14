@extends('layouts.dashboard')
@section('title', 'Notifications')
@php $header = 'Notifications'; $subheader = 'Your course and feedback assignment updates.'; @endphp
@section('sidebar-nav')
@include('staff.partials.sidebar')
<!--
<div class="nav-section-label">Main</div><a href="{{ route('staff.dashboard') }}" class="nav-link">Dashboard</a>
<div class="nav-section-label">Feedback</div><a href="{{ route('staff.feedback.index') }}" class="nav-link">Assigned Feedback</a><a href="{{ route('staff.notifications.index') }}" class="nav-link active">Notifications</a>
-->
@endsection
@section('content')
<div class="card"><div class="card-header"><h3>All Notifications</h3><form method="POST" action="{{ route('staff.notifications.read-all') }}">@csrf<button class="btn-secondary btn-sm">Mark all as read</button></form></div>
<div class="card-body">@forelse($notifications as $notification)<a href="{{ route('staff.notifications.read', $notification->id) }}" style="display:block;padding:14px;border-bottom:1px solid #E2E8F0;text-decoration:none;color:#1E293B;background:{{ $notification->read_at ? '#fff' : '#F5F3FF' }}"><strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><p style="margin-top:4px;color:#64748B">{{ $notification->data['message'] ?? '' }}</p><small style="color:#94A3B8">{{ $notification->created_at->format('d M Y, h:i A') }}</small></a>@empty<p style="padding:30px;text-align:center;color:#64748B">No notifications yet.</p>@endforelse</div><div style="padding:16px">{{ $notifications->links() }}</div></div>
@endsection
