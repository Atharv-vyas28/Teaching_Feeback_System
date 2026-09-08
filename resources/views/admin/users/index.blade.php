@extends('layouts.dashboard')
@section('title', 'Manage Users')
@php $header = 'Users'; $subheader = 'Manage all system users'; @endphp
@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Add User</a>
@endsection
@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection
@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
    <!-- Filter -->
    <div class="p-4 border-b border-slate-100 flex flex-wrap gap-3">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, roll..." class="form-input" style="width:220px">
            <select name="role" class="form-select" style="width:140px">
                <option value="">All Roles</option>
                @foreach(['admin','faculty','staff','student'] as $r)
                <option value="{{ $r }}" {{ request('role')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button class="btn-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Clear</a>
        </form>
    </div>
    <div class="overflow-auto">
        <table class="data-table">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>ID</th><th>Dept</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-slate-400 text-xs">{{ $user->id }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            <div>
                                <div class="font-medium">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->roll_number ?? $user->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-slate-500 text-xs">{{ $user->email }}</td>
                    <td><span class="badge {{ ['admin'=>'badge-purple','faculty'=>'badge-blue','staff'=>'badge-green','student'=>'badge-yellow'][$user->role] ?? 'badge-gray' }}">{{ ucfirst($user->role) }}</span></td>
                    <td class="text-xs text-slate-500">{{ $user->roll_number ?? $user->employee_id ?? '—' }}</td>
                    <td class="text-xs text-slate-500">{{ $user->department->code ?? '—' }}</td>
                    <td><span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                @csrf
                                <button class="btn-warning btn-sm">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn-danger btn-sm" data-confirm="Delete {{ $user->name }}?">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-slate-400 py-8">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $users->links() }}</div>
</div>
@endsection