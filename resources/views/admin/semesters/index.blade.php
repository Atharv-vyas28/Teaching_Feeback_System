@extends('layouts.dashboard')
@section('title', 'Semesters')
@php $header = 'Semesters'; $subheader = 'Manage academic years and semesters.'; @endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Semesters</h3>
    </div>
    <div class="card-body">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesters as $sem)
                    <tr>
                        <td style="font-weight:600; color:#0F172A;">{{ $sem->name }}</td>
                        <td>{{ $sem->academicYear->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($sem->start_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($sem->end_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge {{ $sem->is_current ? 'badge-green' : 'badge-gray' }}">
                                {{ $sem->is_current ? 'Current' : 'Past/Future' }}
                            </span>
                        </td>
                        <td>
                            @if(!$sem->is_current)
                            <form method="POST" action="{{ route('admin.semesters.set-current', $sem) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-primary btn-sm">Set Current</button>
                            </form>
                            @else
                            <span style="font-size:0.8rem;color:#94A3B8;">Active Semester</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">No semesters found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
