<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Course;
use App\Models\ClassSection;
use App\Models\FacultyCourse;
use App\Models\StaffCourse;
use App\Models\CourseEnrollment;
use App\Models\FeedbackQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Departments ────────────────────────────────────────────────────────
        $csDept  = Department::create(['name' => 'Computer Science & Engineering', 'code' => 'CSE', 'is_active' => true]);
        $ecDept  = Department::create(['name' => 'Electronics & Communication',    'code' => 'ECE', 'is_active' => true]);
        $meDept  = Department::create(['name' => 'Mechanical Engineering',         'code' => 'ME',  'is_active' => true]);

        // ── Programs ──────────────────────────────────────────────────────────
        $btech = Program::create(['department_id' => $csDept->id, 'name' => 'B.Tech CSE', 'code' => 'BTCSE', 'duration_years' => 4, 'is_active' => true]);
        Program::create(['department_id' => $ecDept->id, 'name' => 'B.Tech ECE', 'code' => 'BTECE', 'duration_years' => 4, 'is_active' => true]);

        // ── Academic Year & Semester ───────────────────────────────────────────
        $ay = AcademicYear::create(['name' => '2025-26', 'start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'is_current' => true]);
        $sem5 = Semester::create([
            'academic_year_id' => $ay->id,
            'name'       => 'Semester 5 B.Tech',
            'number'     => 5,
            'start_date' => '2025-07-15',
            'end_date'   => '2025-12-15',
            'is_current' => true,
        ]);

        // ── Users: Admin ──────────────────────────────────────────────────────
        $admin = User::create([
            'name'        => 'Admin Controller',
            'email'       => 'admin@iiti.ac.in',
            'password'    => Hash::make('password'),
            'role'        => 'admin',
            'employee_id' => 'ADM001',
            'is_active'   => true,
        ]);

        // ── Users: Faculty ────────────────────────────────────────────────────
        $faculty1 = User::create([
            'name'          => 'Dr. Aris Thorne',
            'email'         => 'aris.thorne@iiti.ac.in',
            'password'      => Hash::make('password'),
            'role'          => 'faculty',
            'department_id' => $csDept->id,
            'employee_id'   => 'FAC001',
            'is_active'     => true,
        ]);
        $faculty2 = User::create([
            'name'          => 'Prof. Peter Parker',
            'email'         => 'peter.parker@iiti.ac.in',
            'password'      => Hash::make('password'),
            'role'          => 'faculty',
            'department_id' => $csDept->id,
            'employee_id'   => 'FAC002',
            'is_active'     => true,
        ]);

        // ── Users: Staff ──────────────────────────────────────────────────────
        $staff1 = User::create([
            'name'          => 'Staff Member One',
            'email'         => 'staff@iiti.ac.in',
            'password'      => Hash::make('password'),
            'role'          => 'staff',
            'department_id' => $csDept->id,
            'employee_id'   => 'STF001',
            'is_active'     => true,
        ]);

        // ── Users: Students ───────────────────────────────────────────────────
        $students = [];
        $studentData = [
            ['name' => 'Alice Johnson',  'email' => 'alice@student.iiti.ac.in',  'roll' => '2021CSE001'],
            ['name' => 'Bob Smith',      'email' => 'bob@student.iiti.ac.in',    'roll' => '2021CSE002'],
            ['name' => 'Carol Williams', 'email' => 'carol@student.iiti.ac.in',  'roll' => '2021CSE003'],
            ['name' => 'David Brown',    'email' => 'david@student.iiti.ac.in',  'roll' => '2021CSE004'],
            ['name' => 'Eva Martinez',   'email' => 'eva@student.iiti.ac.in',    'roll' => '2021CSE005'],
        ];
        foreach ($studentData as $sd) {
            $students[] = User::create([
                'name'             => $sd['name'],
                'email'            => $sd['email'],
                'password'         => Hash::make('password'),
                'role'             => 'student',
                'department_id'    => $csDept->id,
                'program_id'       => $btech->id,
                'roll_number'      => $sd['roll'],
                'current_semester' => 5,
                'is_active'        => true,
            ]);
        }

        // ── Courses ───────────────────────────────────────────────────────────
        $ds  = Course::create(['department_id' => $csDept->id, 'name' => 'Data Structures',       'code' => 'CS301', 'credits' => 4, 'is_active' => true]);
        $os  = Course::create(['department_id' => $csDept->id, 'name' => 'Operating Systems',     'code' => 'CS302', 'credits' => 4, 'is_active' => true]);
        $dbms = Course::create(['department_id' => $csDept->id, 'name' => 'Database Management',  'code' => 'CS303', 'credits' => 3, 'is_active' => true]);
        $cn  = Course::create(['department_id' => $csDept->id, 'name' => 'Computer Networks',     'code' => 'CS304', 'credits' => 3, 'is_active' => true]);

        // ── Class Sections ────────────────────────────────────────────────────
        $sec1 = ClassSection::create(['course_id' => $ds->id,   'semester_id' => $sem5->id, 'section_name' => 'A', 'is_active' => true]);
        $sec2 = ClassSection::create(['course_id' => $os->id,   'semester_id' => $sem5->id, 'section_name' => 'A', 'is_active' => true]);
        $sec3 = ClassSection::create(['course_id' => $dbms->id, 'semester_id' => $sem5->id, 'section_name' => 'A', 'is_active' => true]);
        $sec4 = ClassSection::create(['course_id' => $cn->id,   'semester_id' => $sem5->id, 'section_name' => 'A', 'is_active' => true]);

        // ── Assign Faculty ────────────────────────────────────────────────────
        FacultyCourse::create(['user_id' => $faculty1->id, 'class_section_id' => $sec1->id, 'is_active' => true]);
        FacultyCourse::create(['user_id' => $faculty1->id, 'class_section_id' => $sec2->id, 'is_active' => true]);
        FacultyCourse::create(['user_id' => $faculty2->id, 'class_section_id' => $sec3->id, 'is_active' => true]);
        FacultyCourse::create(['user_id' => $faculty2->id, 'class_section_id' => $sec4->id, 'is_active' => true]);

        // ── Assign Staff ──────────────────────────────────────────────────────
        StaffCourse::create(['user_id' => $staff1->id, 'class_section_id' => $sec1->id, 'is_active' => true]);
        StaffCourse::create(['user_id' => $staff1->id, 'class_section_id' => $sec2->id, 'is_active' => true]);

        // ── Enroll Students ───────────────────────────────────────────────────
        foreach ($students as $student) {
            CourseEnrollment::create(['user_id' => $student->id, 'class_section_id' => $sec1->id, 'status' => 'active']);
            CourseEnrollment::create(['user_id' => $student->id, 'class_section_id' => $sec2->id, 'status' => 'active']);
            CourseEnrollment::create(['user_id' => $student->id, 'class_section_id' => $sec3->id, 'status' => 'active']);
            CourseEnrollment::create(['user_id' => $student->id, 'class_section_id' => $sec4->id, 'status' => 'active']);
        }

        // ── Feedback Questions ────────────────────────────────────────────────
        $questions = [
            ['question_text' => 'How clearly did the faculty explain the concepts?',              'type' => 'rating', 'weight' => 2.00, 'order_position' => 1],
            ['question_text' => 'How punctual was the faculty for the class?',                    'type' => 'rating', 'weight' => 1.00, 'order_position' => 2],
            ['question_text' => 'How well did the faculty address student doubts and queries?',   'type' => 'rating', 'weight' => 1.50, 'order_position' => 3],
            ['question_text' => 'How effective was the use of teaching aids and materials?',      'type' => 'rating', 'weight' => 1.00, 'order_position' => 4],
            ['question_text' => 'Rate the overall quality of the class session.',                 'type' => 'rating', 'weight' => 2.00, 'order_position' => 5],
            ['question_text' => 'Do you have any suggestions to improve the teaching style?',     'type' => 'text',   'weight' => 0.00, 'order_position' => 6, 'is_required' => false],
        ];
        foreach ($questions as $q) {
            FeedbackQuestion::create(array_merge([
                'is_required' => true,
                'is_active'   => true,
            ], $q));
        }

        // ── Sample Class Sessions & Attendance ────────────────────────────────
        $session1 = \App\Models\ClassSession::create([
            'class_section_id' => $sec1->id,
            'conducted_by'     => $faculty1->id,
            'session_date'     => now()->subDays(2),
            'start_time'       => '10:00:00',
            'end_time'         => '11:00:00',
            'topic'            => 'Intro to Data Structures',
            'status'           => 'completed',
        ]);

        $session2 = \App\Models\ClassSession::create([
            'class_section_id' => $sec2->id,
            'conducted_by'     => $faculty1->id,
            'session_date'     => now(),
            'start_time'       => '12:00:00',
            'end_time'         => '13:00:00',
            'topic'            => 'Process Management',
            'status'           => 'completed',
        ]);

        foreach ($students as $student) {
            \App\Models\Attendance::create([
                'class_session_id' => $session1->id,
                'student_id'       => $student->id,
                'marked_by'        => $faculty1->id,
                'status'           => 'present',
                'feedback_enabled' => true,
                'marked_at'        => now()->subDays(2),
            ]);

            \App\Models\Attendance::create([
                'class_session_id' => $session2->id,
                'student_id'       => $student->id,
                'marked_by'        => $faculty1->id,
                'status'           => 'present',
                'feedback_enabled' => true,
                'marked_at'        => now(),
            ]);
        }

        // ── Sample Feedback Sessions ──────────────────────────────────────────
        // Session 1: Closed with some responses
        $fbSession1 = \App\Models\FeedbackSession::create([
            'class_session_id' => $session1->id,
            'status'           => 'closed',
            'opened_at'        => now()->subDays(2),
            'closed_at'        => now()->subDays(1),
            'created_by'       => $faculty1->id,
        ]);
        
        // Session 2: Active (ready for student to submit)
        $fbSession2 = \App\Models\FeedbackSession::create([
            'class_session_id' => $session2->id,
            'status'           => 'active',
            'opened_at'        => now(),
            'created_by'       => $faculty1->id,
        ]);

        // Add eligibility
        foreach ($students as $student) {
            \App\Models\FeedbackEligibility::create(['feedback_session_id' => $fbSession1->id, 'student_id' => $student->id]);
            \App\Models\FeedbackEligibility::create(['feedback_session_id' => $fbSession2->id, 'student_id' => $student->id]);
        }

        // Add Responses for Session 1 (Closed)
        foreach (array_slice($students, 0, 3) as $student) {
            $resp = \App\Models\FeedbackResponse::create(['feedback_session_id' => $fbSession1->id, 'anonymous_token' => \Illuminate\Support\Str::uuid()]);
            foreach (FeedbackQuestion::all() as $q) {
                if ($q->type === 'rating') {
                    \App\Models\FeedbackAnswer::create(['feedback_response_id' => $resp->id, 'feedback_question_id' => $q->id, 'rating_value' => rand(3, 5)]);
                } else {
                    \App\Models\FeedbackAnswer::create(['feedback_response_id' => $resp->id, 'feedback_question_id' => $q->id, 'text_answer' => 'Great class!']);
                }
            }
        }
    }
}
