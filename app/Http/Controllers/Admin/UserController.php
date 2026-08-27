<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'program'])->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('roll_number', 'like', "%{$request->search}%")
                  ->orWhere('employee_id', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'departments', 'programs'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.users.create', compact('departments', 'programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:8|confirmed',
            'role'             => 'required|in:student,faculty,staff,admin',
            'department_id'    => 'nullable|exists:departments,id',
            'program_id'       => 'nullable|exists:programs,id',
            'roll_number'      => 'nullable|string|unique:users,roll_number',
            'employee_id'      => 'nullable|string|unique:users,employee_id',
            'current_semester' => 'nullable|integer|min:1|max:12',
            'phone'            => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'departments', 'programs'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'         => 'nullable|string|min:8|confirmed',
            'role'             => 'required|in:student,faculty,staff,admin',
            'department_id'    => 'nullable|exists:departments,id',
            'program_id'       => 'nullable|exists:programs,id',
            'roll_number'      => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'employee_id'      => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'current_semester' => 'nullable|integer|min:1|max:12',
            'phone'            => 'nullable|string|max:20',
            'is_active'        => 'boolean',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate yourself.');
        }
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }
}
