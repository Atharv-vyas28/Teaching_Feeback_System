@extends('layouts.dashboard')
@section('title', 'Ratings Report')
@php $header = 'Faculty Ratings Report'; $subheader = 'Aggregated faculty performance ratings based on student feedback.'; @endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.ratings') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-select" style="width:220px;">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>{{ $sem->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Faculty Performance Rankings</h3>
        <span class="badge badge-blue">{{ count($ratings) }} results</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Faculty Member</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Rating</th>
                    <th>Responses</th>
                    <th>Status</th>
                    <th>Calculated</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ratings as $i => $r)
                <tr>
                    <td style="font-weight:700;color:#94A3B8;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#1D4ED8;flex-shrink:0;">
                                {{ strtoupper(substr($r->faculty->name ?? 'F', 0, 1)) }}
                            </div>
                            <span style="font-weight:600;">{{ $r->faculty->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $r->section->course->name ?? 'N/A' }}</td>
                    <td>{{ $r->semester->name ?? 'N/A' }}</td>
                    <td>
                        @if($r->overall_weighted_rating)
                        <span style="font-size:1rem;font-weight:800;color:{{ $r->overall_weighted_rating >= 4 ? '#1D4ED8' : ($r->overall_weighted_rating >= 3 ? '#D97706' : '#DC2626') }};">
                            {{ number_format($r->overall_weighted_rating, 1) }}
                        </span>
                        <span style="color:#94A3B8;font-size:0.75rem;">/ 5</span>
                        @else
                        <span style="color:#94A3B8;">N/A</span>
                        @endif
                    </td>
                    <td>{{ $r->response_count }}</td>
                    <td>
                        @if($r->overall_weighted_rating >= 4)
                            <span class="badge badge-green">Excellent</span>
                        @elseif($r->overall_weighted_rating >= 3)
                            <span class="badge badge-yellow">Stable</span>
                        @elseif($r->overall_weighted_rating)
                            <span class="badge badge-red">Needs Improvement</span>
                        @else
                            <span class="badge badge-gray">Pending</span>
                        @endif
                    </td>
                    <td style="color:#94A3B8;font-size:0.8rem;">
                        {{ $r->calculated_at ? \Carbon\Carbon::parse($r->calculated_at)->format('M d, Y') : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="empty-state" style="text-align:center;padding:40px;color:#94A3B8;">No rating results found for selected filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
