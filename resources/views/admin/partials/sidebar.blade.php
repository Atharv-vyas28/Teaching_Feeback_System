<style>
    .admin-sidebar-pro {
        padding: 10px 10px 22px;
        color: #52617b;
    }

    .admin-sidebar-pro .sidebar-user {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 14px 12px;
        margin: 3px 0 18px;
        border: 1px solid #e7eaf2;
        background: linear-gradient(135deg, #fafbff, #f4f6ff);
        border-radius: 14px;
    }

    .admin-sidebar-pro .avatar {
        width: 39px;
        height: 39px;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #6c55e8, #3576f6);
        border-radius: 12px;
        font-size: .85rem;
        font-weight: 800;
        box-shadow: 0 7px 15px rgba(92, 72, 215, .25);
    }

    .admin-sidebar-pro .user-name {
        color: #17213a;
        font-size: .82rem;
        font-weight: 800;
    }

    .admin-sidebar-pro .user-role {
        margin-top: 2px;
        color: #7b88a3;
        font-size: .7rem;
    }

    .admin-sidebar-pro .nav-title {
        margin: 20px 10px 7px;
        color: #a1aabe;
        font-size: .64rem;
        letter-spacing: .1em;
        font-weight: 800;
        text-transform: uppercase;
    }

    .admin-sidebar-pro .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 11px;
        margin: 3px 0;
        border-radius: 10px;
        color: #62708a;
        text-decoration: none;
        font-size: .81rem;
        font-weight: 650;
        transition: .18s ease;
    }

    .admin-sidebar-pro .admin-nav-link:hover {
        color: #5b48d7;
        background: #f1efff;
    }

    .admin-sidebar-pro .admin-nav-link.active {
        color: #fff;
        background: linear-gradient(100deg, #6c55e8, #4d7cf0);
        box-shadow: 0 8px 16px rgba(92, 72, 215, .24);
    }

    .admin-sidebar-pro .admin-nav-link svg {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
        stroke-width: 2;
    }

    .admin-sidebar-pro .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 11px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #dc5263;
        cursor: pointer;
        font-size: .81rem;
        font-weight: 700;
        text-align: left;
    }

    .admin-sidebar-pro .logout-btn:hover {
        background: #fff0f2;
    }
</style>

@php
    $user = auth()->user();
    $initial = strtoupper(substr($user?->name ?? 'A', 0, 1));
@endphp

<div class="admin-sidebar-pro">

    <!-- <div class="sidebar-user">
        <div class="avatar">{{ $initial }}</div>

        <div>
            <div class="user-name">{{ $user?->name ?? 'Admin' }}</div>
            <div class="user-role">Admin Controller</div>
        </div>
    </div> -->

    <div class="nav-title">Overview</div>

    <a
        href="{{ route('admin.dashboard') }}"
        class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2 7-7 7 7 2 2M5 10v10h14V10M9 20v-6h6v6"/>
        </svg>
        Dashboard
    </a>

    <div class="nav-title">People</div>

    <a
        href="{{ route('admin.users.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Users
    </a>

    <div class="nav-title">Academic Management</div>

    <a
        href="{{ route('admin.departments.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V5l7-3 7 3v16M9 21v-4h6v4M8 8h.01M12 8h.01M16 8h.01M8 12h.01M12 12h.01M16 12h.01"/>
        </svg>
        Departments
    </a>

    <a
        href="{{ route('admin.courses.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
        </svg>
        Courses
    </a>

    <a
        href="{{ route('admin.semesters.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.semesters.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="5" width="18" height="16" rx="2"/>
            <path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/>
        </svg>
        Semesters
    </a>

    <a
    href="{{ route('admin.students.directory') }}"
    class="admin-nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
>
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M22 21v-2a4 4 0 0 0-3-3.87"/>
    </svg>
    Student Directory
</a>

    <a
        href="{{ route('admin.enrollments.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="5" y="4" width="14" height="17" rx="2"/>
            <path stroke-linecap="round" d="M9 4V2M15 4V2M8 10h8M8 14h8M8 18h5"/>
        </svg>
        Enrollments
    </a>

    <div class="nav-title">Feedback</div>

    <a
        href="{{ route('admin.feedback-questions.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.feedback-questions.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <path stroke-linecap="round" d="M9.1 9a3 3 0 1 1 5.4 1.8c-.9 1.2-2.5 1.5-2.5 3.2M12 17h.01"/>
        </svg>
        Feedback Questions
    </a>

    <a
        href="{{ route('admin.feedback-sessions.index') }}"
        class="admin-nav-link {{ request()->routeIs('admin.feedback-sessions.*') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.8 9.8 0 0 1-4.8-1.3L3 20l1.4-4A8.6 8.6 0 0 1 3 11.5a8.5 8.5 0 0 1 9-8.5 8.5 8.5 0 0 1 9 8.5z"/>
            <path stroke-linecap="round" d="M8 12h.01M12 12h.01M16 12h.01"/>
        </svg>
        Feedback Sessions
    </a>

    <a href="{{ route('admin.staff-assignments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.staff-assignments.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M19 8v6m3-3h-6"/></svg>
        Staff Assignments
    </a>

    <div class="nav-title">Reports</div>

    <a
        href="{{ route('admin.reports.attendance') }}"
        class="admin-nav-link {{ request()->routeIs('admin.reports.attendance') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
        </svg>
        Attendance Report
    </a>

    <a
        href="{{ route('admin.reports.ratings') }}"
        class="admin-nav-link {{ request()->routeIs('admin.reports.ratings') ? 'active' : '' }}"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.8-5.4 2.8 1-6.1-4.4-4.3 6.1-.9L12 3z"/>
        </svg>
        Ratings Report
    </a>

    <a href="{{ route('admin.reports.feedback-participation') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.feedback-participation') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16v-4m4 4V8m4 8v-6"/></svg>
        Feedback Participation
    </a>

    <div class="nav-title">Account</div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button
            type="submit"
            class="logout-btn"
            onclick="return confirm('Do you want to sign out?')"
        >
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-6"/>
            </svg>
            Logout
        </button>
    </form>
</div>
