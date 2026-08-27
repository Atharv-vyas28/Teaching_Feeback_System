@extends('layouts.dashboard')
@section('title', 'Add User')
@php $header = 'Add User'; $subheader = 'Create a new system user'; @endphp
@section('page-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">← Back</a>
@endsection
@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection
@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-input" required minlength="8">
        </div>
        <div>
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
                @foreach(['student','faculty','staff','admin'] as $r)
                <option value="{{ $r }}" {{ old('role')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Program</label>
            <select name="program_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}" {{ old('program_id')==$prog->id?'selected':'' }}>{{ $prog->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Roll Number (students)</label>
            <input type="text" name="roll_number" value="{{ old('roll_number') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Employee ID (faculty/staff)</label>
            <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Current Semester (students)</label>
            <input type="number" name="current_semester" value="{{ old('current_semester') }}" class="form-input" min="1" max="12">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
        </div>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-primary">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection