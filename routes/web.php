<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\FeedbackQuestionController;
use App\Http\Controllers\Admin\FeedbackSessionController as AdminFeedbackSessionController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\StaffAssignmentController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboard;
use App\Http\Controllers\Faculty\AttendanceController as FacultyAttendance;
use App\Http\Controllers\Faculty\FeedbackSessionController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Staff\AttendanceController as StaffAttendance;
use App\Http\Controllers\Staff\FeedbackSessionController as StaffFeedback;
use App\Http\Controllers\Staff\NotificationController as StaffNotification;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\FeedbackController;

// Auth routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Default root redirect
Route::get('/', fn() => redirect()->route('login'));

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get(
    '/students-directory',
    [UserController::class, 'studentDirectory']
)->name('students.directory');

Route::get(
    '/students-directory/{student}',
    [UserController::class, 'studentDetails']
)->name('students.details');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

    // Departments & Programs
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::get('/departments/{department}/programs', [DepartmentController::class, 'programs'])->name('departments.programs');
    Route::post('/departments/{department}/programs', [DepartmentController::class, 'storeProgram'])->name('departments.programs.store');

    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}/sections', [CourseController::class, 'sections'])->name('courses.sections');
    Route::post('/courses/{course}/sections', [CourseController::class, 'storeSection'])->name('courses.sections.store');

    // Enrollments
    Route::get('/enrollments', [CourseController::class, 'enrollments'])->name('enrollments.index');
    Route::post('/enrollments', [CourseController::class, 'storeEnrollment'])->name('enrollments.store');
    Route::post('/assign-faculty', [CourseController::class, 'assignFaculty'])->name('faculty.assign');
    Route::post('/assign-staff', [CourseController::class, 'assignStaff'])->name('staff.assign');
    Route::get('/staff-assignments', [StaffAssignmentController::class, 'index'])->name('staff-assignments.index');
    Route::post('/staff-assignments', [StaffAssignmentController::class, 'store'])->name('staff-assignments.store');
    Route::post('/staff-assignments/{assignment}/deactivate', [StaffAssignmentController::class, 'deactivate'])->name('staff-assignments.deactivate');

    // Semesters
    Route::get('/semesters', [SemesterController::class, 'index'])->name('semesters.index');
    Route::post('/semesters/academic-years', [SemesterController::class, 'storeAcademicYear'])->name('academic-years.store');
    Route::post('/semesters', [SemesterController::class, 'storeSemester'])->name('semesters.store');
    Route::post('/semesters/{semester}/set-current', [SemesterController::class, 'setCurrentSemester'])->name('semesters.set-current');

    // Feedback Questions
    Route::get('/feedback-questions', [FeedbackQuestionController::class, 'index'])->name('feedback-questions.index');
    Route::post('/feedback-questions', [FeedbackQuestionController::class, 'store'])->name('feedback-questions.store');
    Route::put('/feedback-questions/{feedbackQuestion}', [FeedbackQuestionController::class, 'update'])->name('feedback-questions.update');
    Route::delete('/feedback-questions/{feedbackQuestion}', [FeedbackQuestionController::class, 'destroy'])->name('feedback-questions.destroy');
    Route::post('/feedback-questions/reorder', [FeedbackQuestionController::class, 'reorder'])->name('feedback-questions.reorder');
    Route::post('/feedback-questions/{feedbackQuestion}/toggle', [FeedbackQuestionController::class, 'toggle'])->name('feedback-questions.toggle');

    // Reports
    Route::get('/reports/attendance', [ReportsController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('/reports/attendance/export', [ReportsController::class, 'exportAttendanceExcel'])->name('reports.attendance.export');
    Route::get('/reports/attendance/summary', [ReportsController::class, 'attendanceSummary'])->name('reports.attendance.summary');
    Route::get('/reports/ratings', [ReportsController::class, 'ratingsReport'])->name('reports.ratings');
    Route::get('/reports/feedback-participation', [ReportsController::class, 'feedbackParticipationByAttendance'])->name('reports.feedback-participation');

    // Feedback Sessions – add create/store
    Route::get('/feedback-sessions', [AdminFeedbackSessionController::class, 'index'])->name('feedback-sessions.index');
    Route::get('/feedback-sessions/create', [AdminFeedbackSessionController::class, 'create'])->name('feedback-sessions.create');
    Route::post('/feedback-sessions', [AdminFeedbackSessionController::class, 'store'])->name('feedback-sessions.store');
    Route::get('/feedback-sessions/{session}/responses', [AdminFeedbackSessionController::class, 'responses'])->name('feedback-sessions.responses');
    Route::post('/feedback-sessions/{session}/release', [AdminFeedbackSessionController::class, 'release'])->name('feedback-sessions.release');
    Route::post('/feedback-sessions/{session}/assign-staff', [AdminFeedbackSessionController::class, 'assignStaff'])->name('feedback-sessions.assign-staff');
});

// ─── Faculty ─────────────────────────────────────────────────────────────────
Route::prefix('faculty')->name('faculty.')->middleware(['auth', 'role:faculty'])->group(function () {
    Route::get('/dashboard', [FacultyDashboard::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('/attendance/sessions', [FacultyAttendance::class, 'sessions'])->name('attendance.sessions');
    Route::get('/attendance/create', [FacultyAttendance::class, 'create'])->name('attendance.create');
    Route::post('/attendance/sessions', [FacultyAttendance::class, 'storeSession'])->name('attendance.store-session');
    Route::get('/attendance/{classSession}/take', [FacultyAttendance::class, 'take'])->name('attendance.take');
    Route::post('/attendance/{classSession}/save', [FacultyAttendance::class, 'save'])->name('attendance.save');
    Route::get('/attendance/summary', [FacultyAttendance::class, 'summary'])->name('attendance.summary');
    Route::get('/attendance/export', [FacultyAttendance::class, 'exportExcel'])->name('attendance.export');
    Route::get('/attendance/{classSession}/analytics', [FacultyAttendance::class, 'analytics'])->name('attendance.analytics');

    // Feedback sessions
    Route::get('/feedback', [FeedbackSessionController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{classSession}/create', [FeedbackSessionController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/{classSession}', [FeedbackSessionController::class, 'store'])->name('feedback.store');
    Route::post('/feedback-session/{feedbackSession}/open', [FeedbackSessionController::class, 'open'])->name('feedback.open');
    Route::post('/feedback-session/{feedbackSession}/close', [FeedbackSessionController::class, 'close'])->name('feedback.close');
    Route::get('/feedback-session/{feedbackSession}/analytics', [FeedbackSessionController::class, 'analytics'])->name('feedback.analytics');
    Route::get('/my-ratings', [FeedbackSessionController::class, 'myRatings'])->name('feedback.my-ratings');
});

// ─── Staff ────────────────────────────────────────────────────────────────────
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('/attendance/sessions', [StaffAttendance::class, 'sessions'])->name('attendance.sessions');
    Route::get('/attendance/history', [StaffAttendance::class, 'history'])->name('attendance.history');
    Route::get('/attendance/create', [StaffAttendance::class, 'create'])->name('attendance.create');
    Route::post('/attendance/sessions', [StaffAttendance::class, 'storeSession'])->name('attendance.store-session');
    Route::get('/attendance/{classSession}/take', [StaffAttendance::class, 'take'])->name('attendance.take');
    Route::post('/attendance/{classSession}/save', [StaffAttendance::class, 'save'])->name('attendance.save');
    Route::get('/feedback', [StaffFeedback::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedbackSession}/attendance', [StaffAttendance::class, 'takeFeedbackDay'])->name('feedback.attendance.take');
    Route::post('/feedback/{feedbackSession}/attendance', [StaffAttendance::class, 'saveFeedbackDay'])->name('feedback.attendance.save');
    Route::post('/feedback/{feedbackSession}/release', [StaffFeedback::class, 'release'])->name('feedback.release');
    Route::post('/feedback/{feedbackSession}/close', [StaffFeedback::class, 'close'])->name('feedback.close');
    Route::get('/notifications', [StaffNotification::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [StaffNotification::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifications/{notification}', [StaffNotification::class, 'read'])->name('notifications.read');
});

// ─── Student ─────────────────────────────────────────────────────────────────
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    Route::get('/attendance', [StudentDashboard::class, 'attendance'])->name('attendance');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedbackSession}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/feedback/{feedbackSession}/submit', [FeedbackController::class, 'submit'])->name('feedback.submit');
    Route::get('/feedback/{feedbackSession}/confirmation', [FeedbackController::class, 'confirmation'])->name('feedback.confirmation');
});
