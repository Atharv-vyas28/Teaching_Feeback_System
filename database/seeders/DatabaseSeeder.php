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
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackResponse;
use App\Models\FeedbackAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // Departments
        // ================================================================

        $csDept = Department::create([
            'name' => 'Computer Science & Engineering',
            'code' => 'CSE',
            'is_active' => true,
        ]);

        $ecDept = Department::create([
            'name' => 'Electronics & Communication',
            'code' => 'ECE',
            'is_active' => true,
        ]);

        $meDept = Department::create([
            'name' => 'Mechanical Engineering',
            'code' => 'ME',
            'is_active' => true,
        ]);


        // ================================================================
        // Programs
        // ================================================================

        $btechCse = Program::create([
            'department_id' => $csDept->id,
            'name' => 'B.Tech CSE',
            'code' => 'BTCSE',
            'duration_years' => 4,
            'is_active' => true,
        ]);

        Program::create([
            'department_id' => $ecDept->id,
            'name' => 'B.Tech ECE',
            'code' => 'BTECE',
            'duration_years' => 4,
            'is_active' => true,
        ]);


        // ================================================================
        // Academic Year & Semester
        // ================================================================

        $academicYear = AcademicYear::create([
            'name' => '2025-26',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_current' => true,
        ]);

        $semester5 = Semester::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Semester 5 B.Tech',
            'number' => 5,
            'start_date' => '2025-07-15',
            'end_date' => '2025-12-15',
            'is_current' => true,
        ]);


        // ================================================================
        // Admin
        // ================================================================

        $admin = User::create([
            'name' => 'Admin Controller',
            'email' => 'admin@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'is_active' => true,
        ]);


        // ================================================================
        // Faculty
        // ================================================================

        $faculty1 = User::create([
            'name' => 'Dr. Aris Thorne',
            'email' => 'aris.thorne@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $csDept->id,
            'employee_id' => 'FAC001',
            'is_active' => true,
        ]);

        $faculty2 = User::create([
            'name' => 'Prof. Peter Parker',
            'email' => 'peter.parker@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'department_id' => $csDept->id,
            'employee_id' => 'FAC002',
            'is_active' => true,
        ]);


        // ================================================================
        // Staff
        // ================================================================

        $staff1 = User::create([
            'name' => 'Staff Member One',
            'email' => 'staff@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $csDept->id,
            'employee_id' => 'STF001',
            'is_active' => true,
        ]);

        $staff2 = User::create([
            'name' => 'Staff Member Two',
            'email' => 'staff2@iiti.ac.in',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department_id' => $csDept->id,
            'employee_id' => 'STF002',
            'is_active' => true,
        ]);


        // ================================================================
        // Students
        // ================================================================

        $students = [];

        $studentData = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@student.iiti.ac.in',
                'roll' => '2021CSE001',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@student.iiti.ac.in',
                'roll' => '2021CSE002',
            ],
            [
                'name' => 'Carol Williams',
                'email' => 'carol@student.iiti.ac.in',
                'roll' => '2021CSE003',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@student.iiti.ac.in',
                'roll' => '2021CSE004',
            ],
            [
                'name' => 'Eva Martinez',
                'email' => 'eva@student.iiti.ac.in',
                'roll' => '2021CSE005',
            ],
        ];

        foreach ($studentData as $data) {
            $students[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'department_id' => $csDept->id,
                'program_id' => $btechCse->id,
                'roll_number' => $data['roll'],
                'current_semester' => 5,
                'is_active' => true,
            ]);
        }


        // ================================================================
        // Courses
        // ================================================================

        $ds = Course::create([
            'department_id' => $csDept->id,
            'name' => 'Data Structures',
            'code' => 'CS301',
            'credits' => 4,
            'is_active' => true,
        ]);

        $os = Course::create([
            'department_id' => $csDept->id,
            'name' => 'Operating Systems',
            'code' => 'CS302',
            'credits' => 4,
            'is_active' => true,
        ]);

        $dbms = Course::create([
            'department_id' => $csDept->id,
            'name' => 'Database Management',
            'code' => 'CS303',
            'credits' => 3,
            'is_active' => true,
        ]);

        $cn = Course::create([
            'department_id' => $csDept->id,
            'name' => 'Computer Networks',
            'code' => 'CS304',
            'credits' => 3,
            'is_active' => true,
        ]);


        // ================================================================
        // Class Sections
        // ================================================================

        $secDS = ClassSection::create([
            'course_id' => $ds->id,
            'semester_id' => $semester5->id,
            'section_name' => 'A',
            'is_active' => true,
        ]);

        $secOS = ClassSection::create([
            'course_id' => $os->id,
            'semester_id' => $semester5->id,
            'section_name' => 'A',
            'is_active' => true,
        ]);

        $secDBMS = ClassSection::create([
            'course_id' => $dbms->id,
            'semester_id' => $semester5->id,
            'section_name' => 'A',
            'is_active' => true,
        ]);

        $secCN = ClassSection::create([
            'course_id' => $cn->id,
            'semester_id' => $semester5->id,
            'section_name' => 'A',
            'is_active' => true,
        ]);


        // ================================================================
        // Faculty Assignments
        //
        // Data Structures has TWO faculty.
        // This lets us test:
        //
        // Course
        //   -> View Faculty
        //       -> Dr. Aris Thorne
        //       -> Prof. Peter Parker
        //
        // Each faculty has an independent feedback session.
        // ================================================================

        FacultyCourse::create([
            'user_id' => $faculty1->id,
            'class_section_id' => $secDS->id,
            'is_active' => true,
        ]);

        FacultyCourse::create([
            'user_id' => $faculty2->id,
            'class_section_id' => $secDS->id,
            'is_active' => true,
        ]);

        FacultyCourse::create([
            'user_id' => $faculty1->id,
            'class_section_id' => $secOS->id,
            'is_active' => true,
        ]);

        FacultyCourse::create([
            'user_id' => $faculty2->id,
            'class_section_id' => $secDBMS->id,
            'is_active' => true,
        ]);

        FacultyCourse::create([
            'user_id' => $faculty2->id,
            'class_section_id' => $secCN->id,
            'is_active' => true,
        ]);


        // ================================================================
        // Course Enrollments
        // ================================================================

        foreach ($students as $student) {

            foreach ([
                $secDS,
                $secOS,
                $secDBMS,
                $secCN,
            ] as $section) {

                CourseEnrollment::create([
                    'user_id' => $student->id,
                    'class_section_id' => $section->id,
                    'status' => 'active',
                ]);
            }
        }


        // ================================================================
        // Feedback Questions
        // ================================================================

        $questions = [
            [
                'question_text' =>
                    'How clearly did the faculty explain the concepts?',
                'type' => 'rating',
                'weight' => 2.00,
                'order_position' => 1,
            ],
            [
                'question_text' =>
                    'How punctual was the faculty for the class?',
                'type' => 'rating',
                'weight' => 1.00,
                'order_position' => 2,
            ],
            [
                'question_text' =>
                    'How well did the faculty address student doubts and queries?',
                'type' => 'rating',
                'weight' => 1.50,
                'order_position' => 3,
            ],
            [
                'question_text' =>
                    'How effective was the use of teaching aids and materials?',
                'type' => 'rating',
                'weight' => 1.00,
                'order_position' => 4,
            ],
            [
                'question_text' =>
                    'Rate the overall quality of the class session.',
                'type' => 'rating',
                'weight' => 2.00,
                'order_position' => 5,
            ],
            [
                'question_text' =>
                    'Do you have any suggestions to improve the teaching style?',
                'type' => 'text',
                'weight' => 0.00,
                'order_position' => 6,
                'is_required' => false,
            ],
        ];

        foreach ($questions as $question) {

            FeedbackQuestion::create(
                array_merge(
                    [
                        'is_required' => true,
                        'is_active' => true,
                    ],
                    $question
                )
            );
        }


        // ================================================================
        // REGULAR CLASS SESSIONS
        //
        // These are normal faculty attendance sessions.
        // source = regular
        // ================================================================

        $regularDS1 = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $faculty1->id,
            'session_date' => now()->subDays(10)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'topic' => 'Introduction to Data Structures',
            'status' => 'completed',
        ]);

        $regularDS2 = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $faculty1->id,
            'session_date' => now()->subDays(7)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'topic' => 'Arrays and Linked Lists',
            'status' => 'completed',
        ]);

        $regularOS = ClassSession::create([
            'class_section_id' => $secOS->id,
            'conducted_by' => $faculty1->id,
            'session_date' => now()->subDays(5)->toDateString(),
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'topic' => 'Process Management',
            'status' => 'completed',
        ]);


        // ================================================================
        // REGULAR ATTENDANCE
        // ================================================================

        foreach ($students as $student) {

            Attendance::create([
                'class_session_id' => $regularDS1->id,
                'student_id' => $student->id,
                'marked_by' => $faculty1->id,
                'status' => 'present',
                'feedback_enabled' => false,
                'source' => 'regular',
                'marked_at' => now()->subDays(10),
            ]);

            Attendance::create([
                'class_session_id' => $regularDS2->id,
                'student_id' => $student->id,
                'marked_by' => $faculty1->id,
                'status' => $student->id === $students[4]->id
                    ? 'absent'
                    : 'present',
                'feedback_enabled' => false,
                'source' => 'regular',
                'marked_at' => now()->subDays(7),
            ]);

            Attendance::create([
                'class_session_id' => $regularOS->id,
                'student_id' => $student->id,
                'marked_by' => $faculty1->id,
                'status' => 'present',
                'feedback_enabled' => false,
                'source' => 'regular',
                'marked_at' => now()->subDays(5),
            ]);
        }


        // ================================================================
        // FEEDBACK-DAY CLASS SESSION #1
        //
        // Dr. Aris Thorne
        //
        // CLOSED / DONE
        // ================================================================

        $feedbackDay1 = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $staff1->id,
            'session_date' => now()->subDays(4)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'topic' => 'Teaching Feedback - Dr. Aris Thorne',
            'status' => 'completed',
        ]);

        $feedbackSession1 = FeedbackSession::create([
            'class_section_id' => $secDS->id,
            'class_session_id' => $feedbackDay1->id,
            'faculty_id' => $faculty1->id,
            'created_by' => $admin->id,
            'assigned_staff_id' => $staff1->id,
            'status' => 'closed',
            'release_at' => now()->subDays(4),
            'opened_at' => now()->subDays(4),
            'closed_at' => now()->subDays(3),
            'duration_minutes' => 60,
            'is_released' => true,
        ]);


        // ================================================================
        // FEEDBACK-DAY STAFF ASSIGNMENTS
        //
        // Multiple staff can be assigned to one feedback session.
        // ================================================================

        StaffCourse::create([
            'user_id' => $staff1->id,
            'class_section_id' => $secDS->id,
            'feedback_session_id' => $feedbackSession1->id,
            'assigned_by' => $admin->id,
            'is_active' => true,
            'status' => 'active',
            'assigned_at' => now()->subDays(5),
        ]);

        StaffCourse::create([
            'user_id' => $staff2->id,
            'class_section_id' => $secDS->id,
            'feedback_session_id' => $feedbackSession1->id,
            'assigned_by' => $admin->id,
            'is_active' => true,
            'status' => 'active',
            'assigned_at' => now()->subDays(5),
        ]);


        // ================================================================
        // FEEDBACK-DAY ATTENDANCE
        //
        // IMPORTANT:
        // This is NOT regular attendance.
        // source = feedback_day
        // ================================================================

        foreach ($students as $student) {

            Attendance::create([
                'class_session_id' => $feedbackDay1->id,
                'student_id' => $student->id,
                'marked_by' => $staff1->id,
                'status' => 'present',
                'feedback_enabled' => true,
                'source' => 'feedback_day',
                'marked_at' => now()->subDays(4),
            ]);
        }


        // ================================================================
        // ELIGIBILITY FOR CLOSED SESSION
        // ================================================================

        foreach ($students as $student) {

            // Two regular sessions:
            //
            // Alice/Bob/Carol/David = 100%
            // Eva = 50%
            //
            $weight = $student->id === $students[4]->id
                ? 0.5000
                : 1.0000;

            FeedbackEligibility::create([
                'feedback_session_id' => $feedbackSession1->id,
                'student_id' => $student->id,
                'has_submitted' => false,
                'submitted_at' => null,
                'anonymous_token' => null,
                'included_in_score' => true,
                'attendance_weight' => $weight,
            ]);
        }


        // ================================================================
        // RESPONSES FOR CLOSED SESSION
        // ================================================================

        foreach (array_slice($students, 0, 3) as $student) {

            $eligibility = FeedbackEligibility::where(
                'feedback_session_id',
                $feedbackSession1->id
            )
                ->where(
                    'student_id',
                    $student->id
                )
                ->first();

            $token = (string) Str::uuid();

            $response = FeedbackResponse::create([
                'feedback_session_id' => $feedbackSession1->id,
                'anonymous_token' => $token,
                'submitted_at' => now()->subDays(3),
            ]);

            $eligibility->update([
                'has_submitted' => true,
                'submitted_at' => now()->subDays(3),
                'anonymous_token' => $token,
            ]);

            foreach (FeedbackQuestion::all() as $question) {

                if ($question->type === 'rating') {

                    FeedbackAnswer::create([
                        'feedback_response_id' => $response->id,
                        'feedback_question_id' => $question->id,
                        'rating_value' => rand(3, 5),
                    ]);

                } else {

                    FeedbackAnswer::create([
                        'feedback_response_id' => $response->id,
                        'feedback_question_id' => $question->id,
                        'text_answer' => 'Great class!',
                    ]);
                }
            }
        }


        // ================================================================
        // FEEDBACK-DAY CLASS SESSION #2
        //
        // Prof. Peter Parker
        //
        // ACTIVE / ONGOING
        // ================================================================

        $feedbackDay2 = ClassSession::create([
            'class_section_id' => $secDS->id,
            'conducted_by' => $staff1->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->subMinutes(15)->format('H:i:s'),
            'end_time' => now()->addMinutes(45)->format('H:i:s'),
            'topic' => 'Teaching Feedback - Prof. Peter Parker',
            'status' => 'ongoing',
        ]);

        $feedbackSession2 = FeedbackSession::create([
            'class_section_id' => $secDS->id,
            'class_session_id' => $feedbackDay2->id,
            'faculty_id' => $faculty2->id,
            'created_by' => $admin->id,
            'assigned_staff_id' => $staff1->id,
            'status' => 'active',
            'release_at' => now()->subMinutes(15),
            'opened_at' => now()->subMinutes(15),
            'duration_minutes' => 60,
            'is_released' => false,
        ]);


        // ================================================================
        // MULTIPLE STAFF FOR ACTIVE SESSION
        // ================================================================

        StaffCourse::create([
            'user_id' => $staff1->id,
            'class_section_id' => $secDS->id,
            'feedback_session_id' => $feedbackSession2->id,
            'assigned_by' => $admin->id,
            'is_active' => true,
            'status' => 'active',
            'assigned_at' => now()->subMinutes(20),
        ]);

        StaffCourse::create([
            'user_id' => $staff2->id,
            'class_section_id' => $secDS->id,
            'feedback_session_id' => $feedbackSession2->id,
            'assigned_by' => $admin->id,
            'is_active' => true,
            'status' => 'active',
            'assigned_at' => now()->subMinutes(20),
        ]);


        // ================================================================
        // FEEDBACK-DAY ATTENDANCE FOR ACTIVE SESSION
        // ================================================================

        foreach ($students as $student) {

            Attendance::create([
                'class_session_id' => $feedbackDay2->id,
                'student_id' => $student->id,
                'marked_by' => $staff1->id,
                'status' => 'present',
                'feedback_enabled' => true,
                'source' => 'feedback_day',
                'marked_at' => now()->subMinutes(10),
            ]);

        }


        // ================================================================
        // ELIGIBILITY FOR ACTIVE SESSION
        // ================================================================

        foreach ($students as $student) {

            FeedbackEligibility::create([
                'feedback_session_id' => $feedbackSession2->id,
                'student_id' => $student->id,
                'has_submitted' => false,
                'submitted_at' => null,
                'anonymous_token' => null,
                'included_in_score' => false,
                'attendance_weight' => null,
            ]);
        }


        // ================================================================
        // NO FEEDBACK SESSION FOR OS
        //
        // This gives us:
        //
        // Operating Systems
        //      -> Faculty: Dr. Aris Thorne
        //      -> Remaining
        //
        // Useful for testing the "Open Feedback" button.
        // ================================================================


        // ================================================================
        // NO FEEDBACK SESSION FOR DBMS / CN
        //
        // These also remain in the Remaining state.
        // ================================================================
    }
}