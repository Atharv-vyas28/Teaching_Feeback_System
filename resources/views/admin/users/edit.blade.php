@extends('layouts.dashboard')
@section('title', 'Edit User')
@php $header = 'Edit User: '.$user->name; $subheader = 'Update user information'; @endphp
@section('page-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">← Back</a>
@endsection
@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection
@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">New Password (leave blank to keep)</label>
            <input type="password" name="password" class="form-input" minlength="8">
        </div>
        <div>
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-input">
        </div>
        <div>
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
                @foreach(['student','faculty','staff','admin'] as $r)
                <option value="{{ $r }}" {{ old('role',$user->role)===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id',$user->department_id)==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Program</label>
            <select name="program_id" class="form-select">
                <option value="">— Select —</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}" {{ old('program_id',$user->program_id)==$prog->id?'selected':'' }}>{{ $prog->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Roll Number</label>
            <input type="text" name="roll_number" value="{{ old('roll_number',$user->roll_number) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Employee ID</label>
            <input type="text" name="employee_id" value="{{ old('employee_id',$user->employee_id) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Current Semester</label>
            <input type="number" name="current_semester" value="{{ old('current_semester',$user->current_semester) }}" class="form-input" min="1" max="12">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="form-input">
        </div>
        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',$user->is_active)?'checked':'' }} class="rounded">
            <label for="is_active" class="form-label mb-0">Active</label>
        </div>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection