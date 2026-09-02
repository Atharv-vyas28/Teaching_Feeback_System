@extends('layouts.dashboard')
@section('title', 'Enrollments')
@php $header = 'Student Enrollments'; $subheader = 'Manage student enrollments and assign faculty to sections.'; @endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    {{-- Enroll Student --}}
    <div class="card">
        <div class="card-header"><h3>Enroll Student</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.enrollments.store') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label">Student</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select student...</option>
                        @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->roll_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->course->name }} - Sec {{ $s->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Enroll Student</button>
            </form>
        </div>
    </div>

    {{-- Assign Faculty --}}
    <div class="card">
        <div class="card-header"><h3>Assign Faculty</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.faculty.assign') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label">Faculty</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select faculty...</option>
                        @foreach(\App\Models\User::where('role','faculty')->get() as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Course Section</label>
                    <select name="class_section_id" class="form-select" required>
                        <option value="">Select section...</option>
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->course->name }} - Sec {{ $s->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">Select semester...</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Assign Faculty</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Recent Enrollments</h3></div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course Section</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $e)
                <tr>
                    <td style="font-weight:600;color:#0F172A;">{{ $e->student->name ?? 'N/A' }} <span style="font-size:0.75rem;color:#94A3B8;">{{ $e->student->roll_number ?? '' }}</span></td>
                    <td>{{ $e->section->course->name ?? 'N/A' }} - {{ $e->section->section_name ?? 'N/A' }}</td>
                    <td>{{ $e->semester->name ?? 'N/A' }}</td>
                    <td><span class="badge {{ $e->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($e->status) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($e->enrolled_at)->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">No enrollments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div style="padding:16px;">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
