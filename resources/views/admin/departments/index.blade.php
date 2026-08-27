@extends('layouts.dashboard')
@section('title', 'Departments')
@php $header = 'Departments & Programs'; $subheader = 'Manage academic departments'; @endphp
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">All Departments</h3>
        </div>
        <div class="overflow-auto">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Code</th><th>Programs</th><th>Members</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td><div class="font-medium">{{ $dept->name }}</div></td>
                        <td><span class="badge badge-blue">{{ $dept->code }}</span></td>
                        <td>{{ $dept->programs_count }}</td>
                        <td>{{ $dept->users_count }}</td>
                        <td><span class="badge {{ $dept->is_active ? 'badge-green' : 'badge-red' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.departments.programs', $dept) }}" class="btn-secondary btn-sm">Programs</a>
                                <button onclick="openEditDept({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ $dept->code }}', '{{ addslashes($dept->description) }}', {{ $dept->is_active ? 1:0 }})" class="btn-secondary btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="btn-danger btn-sm" data-confirm="Delete {{ $dept->name }}?">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-slate-400 py-6">No departments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $departments->links() }}</div>
    </div>

    <!-- Add form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Add Department</h3>
        <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Department Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div>
                <label class="form-label">Code *</label>
                <input type="text" name="code" class="form-input" required maxlength="20" value="{{ old('code') }}">
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn-primary">Add Department</button>
        </form>
    </div>
</div>

<!-- Edit modal (hidden) -->
<div id="editDeptModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-semibold text-slate-800 mb-4">Edit Department</h3>
        <form id="editDeptForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div><label class="form-label">Name</label><input type="text" name="name" id="editDeptName" class="form-input" required></div>
            <div><label class="form-label">Code</label><input type="text" name="code" id="editDeptCode" class="form-input" required></div>
            <div><label class="form-label">Description</label><textarea name="description" id="editDeptDesc" class="form-input" rows="2"></textarea></div>
            <div class="flex items-center gap-2"><input type="checkbox" name="is_active" id="editDeptActive" value="1" class="rounded"><label for="editDeptActive" class="form-label mb-0">Active</label></div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Update</button>
                <button type="button" onclick="closeEditDept()" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openEditDept(id, name, code, desc, active) {
    document.getElementById('editDeptForm').action = '/admin/departments/' + id;
    document.getElementById('editDeptName').value = name;
    document.getElementById('editDeptCode').value = code;
    document.getElementById('editDeptDesc').value = desc;
    document.getElementById('editDeptActive').checked = active === 1;
    document.getElementById('editDeptModal').classList.remove('hidden');
}
function closeEditDept() { document.getElementById('editDeptModal').classList.add('hidden'); }
</script>
@endpush