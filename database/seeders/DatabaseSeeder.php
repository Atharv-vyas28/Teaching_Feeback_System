<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassSection;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\FacultyCourse;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;
use App\Models\FeedbackSession;
use App\Models\Program;
use App\Models\Semester;
use App\Models\StaffCourse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Departments and Programs
        |--------------------------------------------------------------------------
        */

        $cse = Department::create([
            'name' => 'Computer Science & Engineering',
            'code' => 'CSE',
            'is_active' => true,
        ]);

        $ece = Department::create([
            'name' => 'Electronics & Communication Engineering',
            'code' => 'ECE',
            'is_active' => true,
        ]);

        $me = Department::create([
            'name' => 'Mechanical Engineering',
            'code' => 'ME',
            'is_active' => true,
        ]);

        $btechCse = Program::create([
            'department_id' => $cse->id,
            'name' => 'B.Tech CSE',
            'code' => 'BTCSE',
            'duration_years' => 4,
            'is_active' => true,
        ]);

        Program::create([
            'department_id' => $ece->id,
            'name' => 'B.Tech ECE',
            'code' => 'BTECE',
            'duration_years' => 4,
            'is_active' => true,
        ]);

        Program::create([
            'department_id' => $me->id,
            'name' => 'B.Tech Mechanical Engineering',
            'code' => 'BTME',
            'duration_years' => 4,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Academic Year and Current Semester
        |--------------------------------------------------------------------------
        */

        $academicYear = AcademicYear::create([
            'name' => '2025-26',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_current' => true,
        ]);

        $semester = Semester::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Semester 5 B.Tech',
            'number' => 5,
            'start_date' => '2025-07-15',
            'end_date' => '2025-12-15',
            'is_current' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name' => 'Admin Controller',
            'email' => 'admin@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CSE HOD, Faculty, and Staff
        |--------------------------------------------------------------------------
        */

        $cseHod = User::create([
            'name' => 'Dr. Meera Nair',
            'email' => 'hod.cse@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $cse->id,
            'employee_id' => 'HODCSE001',
            'is_head' => true,
            'is_active' => true,
        ]);

        $cseFaculty1 = User::create([
            'name' => 'Dr. Aris Thorne',
            'email' => 'aris.thorne@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $cse->id,
            'employee_id' => 'FACCSE001',
            'is_head' => false,
            'is_active' => true,
        ]);

        $cseFaculty2 = User::create([
            'name' => 'Prof. Peter Parker',
            'email' => 'peter.parker@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $cse->id,
            'employee_id' => 'FACCSE002',
            'is_head' => false,
            'is_active' => true,
        ]);

        $cseStaff1 = User::create([
            'name' => 'CSE Feedback Staff One',
            'email' => 'staff.cse1@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $cse->id,
            'employee_id' => 'STFCSE001',
            'is_active' => true,
        ]);

        $cseStaff2 = User::create([
            'name' => 'CSE Feedback Staff Two',
            'email' => 'staff.cse2@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $cse->id,
            'employee_id' => 'STFCSE002',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ECE HOD, Faculty, and Staff
        |--------------------------------------------------------------------------
        */

        $eceHod = User::create([
            'name' => 'Dr. Rohan Mehta',
            'email' => 'hod.ece@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $ece->id,
            'employee_id' => 'HODECE001',
            'is_head' => true,
            'is_active' => true,
        ]);

        $eceFaculty = User::create([
            'name' => 'Dr. Kavya Iyer',
            'email' => 'kavya.iyer@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $ece->id,
            'employee_id' => 'FACECE001',
            'is_head' => false,
            'is_active' => true,
        ]);

        $eceStaff = User::create([
            'name' => 'ECE Feedback Staff',
            'email' => 'staff.ece@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $ece->id,
            'employee_id' => 'STFECE001',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Mechanical HOD, Faculty, and Staff
        |--------------------------------------------------------------------------
        */

        $meHod = User::create([
            'name' => 'Dr. Vikram Rao',
            'email' => 'hod.me@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $me->id,
            'employee_id' => 'HODME001',
            'is_head' => true,
            'is_active' => true,
        ]);

        $meFaculty = User::create([
            'name' => 'Dr. Neha Kapoor',
            'email' => 'neha.kapoor@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $me->id,
            'employee_id' => 'FACME001',
            'is_head' => false,
            'is_active' => true,
        ]);

        $meStaff = User::create([
            'name' => 'Mechanical Feedback Staff',
            'email' => 'staff.me@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $me->id,
            'employee_id' => 'STFME001',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CSE Students
        |--------------------------------------------------------------------------
        */

        $students = [];

        foreach ([
            ['Alice Johnson', 'alice@student.iiti.ac.in', '2021CSE001'],
            ['Bob Smith', 'bob@student.iiti.ac.in', '2021CSE002'],
            ['Carol Williams', 'carol@student.iiti.ac.in', '2021CSE003'],
            ['David Brown', 'david@student.iiti.ac.in', '2021CSE004'],
            ['Eva Martinez', 'eva@student.iiti.ac.in', '2021CSE005'],
        ] as [$name, $email, $roll]) {
            $students[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'student',
                'department_id' => $cse->id,
                'program_id' => $btechCse->id,
                'roll_number' => $roll,
                'current_semester' => 5,
                'is_active' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        $ds = Course::create([
            'department_id' => $cse->id,
            'name' => 'Data Structures',
            'code' => 'CS301',
            'credits' => 4,
            'is_active' => true,
        ]);

        $os = Course::create([
            'department_id' => $cse->id,
            'name' => 'Operating Systems',
            'code' => 'CS302',
            'credits' => 4,
            'is_active' => true,
        ]);

        $dbms = Course::create([
            'department_id' => $cse->id,
            'name' => 'Database Management Systems',
            'code' => 'CS303',
            'credits' => 3,
            'is_active' => true,
        ]);

        $signals = Course::create([
            'department_id' => $ece->id,
            'name' => 'Signals and Systems',
            'code' => 'EC301',
            'credits' => 4,
            'is_active' => true,
        ]);

        $communication = Course::create([
            'department_id' => $ece->id,
            'name' => 'Digital Communication',
            'code' => 'EC302',
            'credits' => 3,
            'is_active' => true,
        ]);

        $thermodynamics = Course::create([
            'department_id' => $me->id,
            'name' => 'Thermodynamics',
            'code' => 'ME301',
            'credits' => 4,
            'is_active' => true,
        ]);

        $machineDesign = Course::create([
            'department_id' => $me->id,
            'name' => 'Machine Design',
            'code' => 'ME302',
            'credits' => 3,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $secDS = ClassSection::create([
            'course_id' => $ds->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => true,
        ]);

        $secOS = ClassSection::create([
            'course_id' => $os->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => false,
        ]);

        $secDBMS = ClassSection::create([
            'course_id' => $dbms->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => true,
        ]);

        $secSignals = ClassSection::create([
            'course_id' => $signals->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => true,
        ]);

        $secCommunication = ClassSection::create([
            'course_id' => $communication->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => false,
        ]);

        $secThermo = ClassSection::create([
            'course_id' => $thermodynamics->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => true,
        ]);

        $secMachineDesign = ClassSection::create([
            'course_id' => $machineDesign->id,
            'semester_id' => $semester->id,
            'section_name' => 'A',
            'is_active' => true,
            'is_feedback_active' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Faculty Assignments
        |--------------------------------------------------------------------------
        */

        foreach ([
            [$cseFaculty1, $secDS],
            [$cseFaculty2, $secDS],
            [$cseFaculty1, $secOS],
            [$cseHod, $secDBMS],
            [$eceHod, $secSignals],
            [$eceFaculty, $secCommunication],
            [$meHod, $secThermo],
            [$meFaculty, $secMachineDesign],
        ] as [$faculty, $section]) {
            FacultyCourse::create([
                'user_id' => $faculty->id,
                'class_section_id' => $section->id,
                'is_active' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CSE Enrolments
        |--------------------------------------------------------------------------
        */

        foreach ($students as $student) {
            foreach ([$secDS, $secOS, $secDBMS] as $section) {
                CourseEnrollment::create([
                    'user_id' => $student->id,
                    'class_section_id' => $section->id,
                    'status' => 'active',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Feedback Questions
        |--------------------------------------------------------------------------
        */

        foreach ([
            ['Were lectures held regularly and on time?', 'rating', 2.00, 1, true],
            ['Were course and attendance policies communicated clearly?', 'rating', 1.50, 2, true],
            ['Rate the overall teaching quality for this course.', 'rating', 2.00, 3, true],
            ['Suggestions to improve teaching?', 'text', 0.00, 4, false],
        ] as [$text, $type, $weight, $position, $required]) {
            FeedbackQuestion::create([
                'question_text' => $text,
                'type' => $type,
                'weight' => $weight,
                'order_position' => $position,
                'is_required' => $required,
                'is_active' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Regular CSE Class Sessions and Attendance
        |--------------------------------------------------------------------------
        */

        $regularDS = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $cseFaculty1->id,
            'session_date' => now()->subDays(10)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'topic' => 'Data Structures: Arrays',
            'status' => 'completed',
        ]);

        foreach ($students as $student) {
            Attendance::create([
                'class_session_id' => $regularDS->id,
                'student_id' => $student->id,
                'marked_by' => $cseFaculty1->id,
                'status' => 'present',
                'feedback_enabled' => false,
                'source' => 'regular',
                'marked_at' => now()->subDays(10),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Completed CSE Feedback: Data Structures
        |--------------------------------------------------------------------------
        */

        $feedbackDay = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $cseStaff1->id,
            'session_date' => now()->subDays(4)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'topic' => 'Teaching Feedback',
            'status' => 'completed',
        ]);

        $feedbackSession = FeedbackSession::create([
            'class_section_id' => $secDS->id,
            'class_session_id' => $feedbackDay->id,
            'created_by' => $cseHod->id,
            'assigned_staff_id' => $cseStaff1->id,
            'status' => 'closed',
            'release_at' => now()->subDays(4),
            'opened_at' => now()->subDays(4),
            'closed_at' => now()->subDays(3),
            'duration_minutes' => 60,
            'is_released' => true,
        ]);

        foreach ([$cseStaff1, $cseStaff2] as $staff) {
            StaffCourse::create([
                'user_id' => $staff->id,
                'class_section_id' => $secDS->id,
                'feedback_session_id' => $feedbackSession->id,
                'assigned_by' => $cseHod->id,
                'is_active' => true,
                'status' => 'active',
                'assigned_at' => now()->subDays(5),
            ]);
        }

        foreach ($students as $student) {
            Attendance::create([
                'class_session_id' => $feedbackDay->id,
                'student_id' => $student->id,
                'marked_by' => $cseStaff1->id,
                'status' => 'present',
                'feedback_enabled' => true,
                'source' => 'feedback_day',
                'marked_at' => now()->subDays(4),
            ]);

            $eligibility = FeedbackEligibility::create([
                'feedback_session_id' => $feedbackSession->id,
                'student_id' => $student->id,
                'has_submitted' => false,
                'submitted_at' => null,
                'anonymous_token' => null,
                'included_in_score' => true,
                'attendance_weight' => 1.0000,
            ]);

            if ($student !== $students[4]) {
                $token = (string) Str::uuid();

                $response = FeedbackResponse::create([
                    'feedback_session_id' => $feedbackSession->id,
                    'anonymous_token' => $token,
                    'submitted_at' => now()->subDays(3),
                ]);

                $eligibility->update([
                    'has_submitted' => true,
                    'submitted_at' => now()->subDays(3),
                    'anonymous_token' => $token,
                ]);

                foreach (FeedbackQuestion::all() as $question) {
                    FeedbackAnswer::create([
                        'feedback_response_id' => $response->id,
                        'feedback_question_id' => $question->id,
                        'rating_value' => $question->type === 'rating'
                            ? random_int(3, 5)
                            : null,
                        'text_answer' => $question->type === 'text'
                            ? 'The course was well organised.'
                            : null,
                    ]);
                }
            }
        }
    }
}