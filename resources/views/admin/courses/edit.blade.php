@extends('layouts.dashboard')

@section('title', 'Edit Course')

@php
    $header = 'Edit Course';
    $subheader = 'Update course information';
@endphp

@section('sidebar-nav')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<style>
    /* =========================================================
       EDIT COURSE PAGE
       ========================================================= */

    .edit-course-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 24px;
    }

    /* =========================================================
       MAIN CARD
       ========================================================= */

    .edit-course-card {
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #dfe5ee;
        border-radius: 16px;

        box-shadow:
            0 4px 14px rgba(31, 54, 88, 0.05);
    }

    /* =========================================================
       CARD HEADER
       ========================================================= */

    .edit-course-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 20px 26px;

        border-bottom: 1px solid #e9edf3;

        background: #ffffff;
    }

    .edit-course-header-left {
        display: flex;
        align-items: center;

        gap: 14px;
    }

    .edit-course-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 11px;

        background: #f0edff;

        color: #5546df;

        font-size: 20px;
        font-weight: 700;
    }

    .edit-course-title {
        margin: 0;

        color: #16243a;

        font-size: 19px;
        line-height: 1.3;

        font-weight: 800;
    }

    .edit-course-subtitle {
        margin: 3px 0 0;

        color: #71809a;

        font-size: 12px;
    }

    .course-name-badge {
        max-width: 320px;

        overflow: hidden;

        padding: 7px 12px;

        border: 1px solid #e4e0ff;
        border-radius: 8px;

        background: #faf9ff;

        color: #5546df;

        font-size: 12px;
        font-weight: 700;

        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* =========================================================
       FORM BODY
       ========================================================= */

    .edit-course-body {
        padding: 24px 26px 22px;
    }

    .course-form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 18px 20px;
    }

    /* =========================================================
       FORM GROUP
       ========================================================= */

    .course-form-group {
        min-width: 0;
    }

    .course-form-group.full-width {
        grid-column: 1 / -1;
    }

    /* =========================================================
       LABELS
       ========================================================= */

    .course-form-label {
        display: block;

        margin: 0 0 7px;

        color: #33445f;

        font-size: 12px;

        font-weight: 750;
    }

    .required-mark {
        color: #dc2626;
    }

    .optional-label {
        margin-left: 4px;

        color: #94a3b8;

        font-size: 11px;

        font-weight: 500;
    }

    /* =========================================================
       INPUT / SELECT / TEXTAREA
       ========================================================= */

    .course-form-input,
    .course-form-select,
    .course-form-textarea {
        width: 100%;

        box-sizing: border-box;

        border: 1px solid #d8e0eb;
        border-radius: 9px;

        background: #ffffff;

        color: #17253b;

        font-family: inherit;

        font-size: 13px;

        outline: none;

        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .course-form-input,
    .course-form-select {
        height: 43px;

        padding: 0 13px;
    }

    .course-form-textarea {
        min-height: 105px;

        padding: 11px 13px;

        line-height: 1.5;

        resize: vertical;
    }

    .course-form-input::placeholder,
    .course-form-textarea::placeholder {
        color: #a0aec0;
    }

    .course-form-input:hover,
    .course-form-select:hover,
    .course-form-textarea:hover {
        border-color: #bdc8d8;
    }

    .course-form-input:focus,
    .course-form-select:focus,
    .course-form-textarea:focus {
        border-color: #6d5dfc;

        box-shadow:
            0 0 0 3px rgba(109, 93, 252, 0.10);
    }

    /* =========================================================
       SELECT
       ========================================================= */

    .course-form-select {
        cursor: pointer;
    }

    /* =========================================================
       ERROR MESSAGE
       ========================================================= */

    .course-form-error {
        margin: 5px 0 0;

        color: #dc2626;

        font-size: 11px;

        line-height: 1.4;
    }

    /* =========================================================
       DESCRIPTION
       ========================================================= */

    .description-wrapper {
        position: relative;
    }

    .description-hint {
        margin-top: 5px;

        color: #8a98ad;

        font-size: 11px;
    }

    /* =========================================================
       STATUS SECTION
       ========================================================= */

    .course-status-section {
        grid-column: 1 / -1;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 15px 17px;

        border: 1px solid #e2e8f0;
        border-radius: 11px;

        background: #f8fafc;
    }

    .course-status-left {
        display: flex;
        align-items: center;

        gap: 12px;
    }

    .course-status-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #e7f8ef;

        color: #059669;

        font-size: 16px;
    }

    .course-status-title {
        margin: 0;

        color: #253650;

        font-size: 13px;

        font-weight: 750;
    }

    .course-status-description {
        margin: 2px 0 0;

        color: #7b89a0;

        font-size: 11px;
    }

    /* =========================================================
       CUSTOM CHECKBOX
       ========================================================= */

    .course-switch {
        position: relative;

        display: inline-flex;
        align-items: center;

        cursor: pointer;
    }

    .course-switch input {
        position: absolute;

        width: 1px;
        height: 1px;

        opacity: 0;
    }

    .course-switch-track {
        position: relative;

        width: 43px;
        height: 24px;

        border-radius: 999px;

        background: #cbd5e1;

        transition: background .2s ease;
    }

    .course-switch-track::after {
        content: "";

        position: absolute;

        top: 3px;
        left: 3px;

        width: 18px;
        height: 18px;

        border-radius: 50%;

        background: #ffffff;

        box-shadow: 0 1px 4px rgba(0,0,0,.18);

        transition: transform .2s ease;
    }

    .course-switch input:checked + .course-switch-track {
        background: #10b981;
    }

    .course-switch input:checked + .course-switch-track::after {
        transform: translateX(19px);
    }

    .course-switch input:focus-visible + .course-switch-track {
        box-shadow:
            0 0 0 3px rgba(16,185,129,.15);
    }

    /* =========================================================
       FORM ACTIONS
       ========================================================= */

    .course-form-actions {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 12px;

        margin-top: 22px;

        padding-top: 18px;

        border-top: 1px solid #e9edf3;
    }

    .form-actions-info {
        color: #8190a6;

        font-size: 11px;
    }

    .form-actions-buttons {
        display: flex;

        align-items: center;

        gap: 9px;
    }

    /* =========================================================
       BUTTONS
       ========================================================= */

    .course-cancel-button,
    .course-update-button {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 40px;

        padding: 0 16px;

        border-radius: 9px;

        font-family: inherit;

        font-size: 13px;

        font-weight: 700;

        text-decoration: none;

        cursor: pointer;

        transition:
            background .18s ease,
            border-color .18s ease,
            transform .18s ease,
            box-shadow .18s ease;
    }

    .course-cancel-button {
        border: 1px solid #d8e0eb;

        background: #ffffff;

        color: #52637d;
    }

    .course-cancel-button:hover {
        background: #f8fafc;

        border-color: #c7d1df;
    }

    .course-update-button {
        border: 1px solid #5546df;

        background: #5546df;

        color: #ffffff;

        box-shadow:
            0 3px 8px rgba(85,70,223,.18);
    }

    .course-update-button:hover {
        background: #493bd1;

        border-color: #493bd1;

        transform: translateY(-1px);

        box-shadow:
            0 5px 12px rgba(85,70,223,.22);
    }

    .course-update-button:active,
    .course-cancel-button:active {
        transform: translateY(0);
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 800px) {

        .edit-course-page {
            padding: 0 0 15px;
        }

        .edit-course-header {
            align-items: flex-start;

            flex-direction: column;

            padding: 18px 20px;
        }

        .course-name-badge {
            max-width: 100%;
        }

        .edit-course-body {
            padding: 20px;
        }

        .course-form-grid {
            grid-template-columns: 1fr;

            gap: 16px;
        }

        .course-form-group.full-width {
            grid-column: auto;
        }

        .course-status-section {
            grid-column: auto;
        }

        .course-form-actions {
            align-items: flex-start;

            flex-direction: column;
        }

        .form-actions-buttons {
            width: 100%;
        }

        .course-cancel-button,
        .course-update-button {
            flex: 1;
        }
    }

    @media (max-width: 480px) {

        .edit-course-header-left {
            align-items: flex-start;
        }

        .edit-course-icon {
            width: 40px;
            height: 40px;

            font-size: 18px;
        }

        .edit-course-title {
            font-size: 17px;
        }

        .edit-course-body {
            padding: 17px;
        }

        .course-status-section {
            align-items: flex-start;

            flex-direction: column;
        }

        .course-status-left {
            width: 100%;
        }
    }
</style>


<div class="edit-course-page">

    <div class="edit-course-card">

        {{-- =========================
             HEADER
        ========================== --}}
        <div class="edit-course-header">

            <div class="edit-course-header-left">

                <div class="edit-course-icon">
                    ✎
                </div>

                <div>
                    <h3 class="edit-course-title">
                        Edit Course
                    </h3>

                    <p class="edit-course-subtitle">
                        Update course information and settings
                    </p>
                </div>

            </div>

            <div class="course-name-badge">
                {{ $course->name }}
            </div>

        </div>


        {{-- =========================
             FORM
        ========================== --}}
        <div class="edit-course-body">

            <form
                method="POST"
                action="{{ route('admin.courses.update', $course) }}"
            >

                @csrf
                @method('PUT')


                <div class="course-form-grid">

                    {{-- COURSE NAME --}}
                    <div class="course-form-group">

                        <label
                            for="name"
                            class="course-form-label"
                        >
                            Course Name
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="course-form-input"
                            value="{{ old('name', $course->name) }}"
                            placeholder="Enter course name"
                            required
                        >

                        @error('name')
                            <p class="course-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- COURSE CODE --}}
                    <div class="course-form-group">

                        <label
                            for="code"
                            class="course-form-label"
                        >
                            Course Code
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            id="code"
                            type="text"
                            name="code"
                            class="course-form-input"
                            value="{{ old('code', $course->code) }}"
                            placeholder="e.g. CS201"
                            required
                        >

                        @error('code')
                            <p class="course-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- DEPARTMENT --}}
                    <div class="course-form-group">

                        <label
                            for="department_id"
                            class="course-form-label"
                        >
                            Department
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            id="department_id"
                            name="department_id"
                            class="course-form-select"
                            required
                        >

                            <option value="">
                                Select department
                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    @selected(
                                        old(
                                            'department_id',
                                            $course->department_id
                                        ) == $department->id
                                    )
                                >
                                    {{ $department->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('department_id')
                            <p class="course-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- CREDITS --}}
                    <div class="course-form-group">

                        <label
                            for="credits"
                            class="course-form-label"
                        >
                            Credits
                            <span class="optional-label">
                                (Optional)
                            </span>
                        </label>

                        <input
                            id="credits"
                            type="number"
                            name="credits"
                            class="course-form-input"
                            min="0"
                            step="0.5"
                            value="{{ old('credits', $course->credits) }}"
                            placeholder="e.g. 3"
                        >

                        @error('credits')
                            <p class="course-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="course-form-group full-width">

                        <label
                            for="description"
                            class="course-form-label"
                        >
                            Description
                            <span class="optional-label">
                                (Optional)
                            </span>
                        </label>

                        <div class="description-wrapper">

                            <textarea
                                id="description"
                                name="description"
                                class="course-form-textarea"
                                rows="4"
                                placeholder="Enter a short description of the course..."
                            >{{ old('description', $course->description) }}</textarea>

                        </div>

                        @error('description')
                            <p class="course-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="description-hint">
                            Provide a brief overview of the course for students and faculty.
                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="course-status-section">

                        <div class="course-status-left">

                            <div class="course-status-icon">
                                ✓
                            </div>

                            <div>

                                <p class="course-status-title">
                                    Course Status
                                </p>

                                <p class="course-status-description">
                                    Active courses are available for use in the system.
                                </p>

                            </div>

                        </div>


                        <label class="course-switch">

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(
                                    old(
                                        'is_active',
                                        $course->is_active
                                    )
                                )
                            >

                            <span class="course-switch-track"></span>

                        </label>

                    </div>

                </div>


                {{-- =========================
                     ACTIONS
                ========================== --}}
                <div class="course-form-actions">

                    <div class="form-actions-info">
                        Fields marked with
                        <span class="required-mark">*</span>
                        are required.
                    </div>

                    <div class="form-actions-buttons">

                        <a
                            href="{{ route('admin.courses.index') }}"
                            class="course-cancel-button"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="course-update-button"
                        >
                            Update Course
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection