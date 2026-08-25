<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('attendance');
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Data API
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        $user = auth()->user();

        $faculty = Faculty::where('email_id', $user->email)->first();

        if (!$faculty) {
            return response()->json([
                'message' => 'Faculty record not found for this user.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Get courses taught by the faculty
        |--------------------------------------------------------------------------
        */
        
        $courseIds = DB::table('teach')
            ->where('faculty_id', $faculty->id)
            ->pluck('course_id');

        $courses = DB::table('courses')
            ->whereIn('course_id', $courseIds)
            ->get();

        $courseData = [];
        $lectureData = [];

        /*
        |--------------------------------------------------------------------------
        | Prepare course & lecture data using Query Builder
        |--------------------------------------------------------------------------
        */

        foreach ($courses as $course) {
            
            $studentIds = DB::table('enroll')
                ->where('course_id', $course->course_id)
                ->pluck('roll_no');

            $students = DB::table('students')
                ->whereIn('roll_no', $studentIds)
                ->get();
            
            $courseData[] = [
                'id'   => $course->course_id,
                'code' => $course->course_id,
                'name' => $course->name,
                
                'students' => $students->map(function ($student) {
                    return [
                        'id'   => $student->roll_no,
                        'roll' => $student->roll_no,
                        'name' => $student->name,
                    ];
                })->toArray(),
            ];

            $lectures = DB::table('lectures')
                ->where('course_id', $course->course_id)
                ->get();

            foreach ($lectures as $lecture) {
                $attendance = [];

                $attendances = DB::table('attendance')
                    ->where('lecture_id', $lecture->lecture_id)
                    ->get();

                foreach ($attendances as $record) {
                    $attendance[$record->roll_no] = ($record->status === 'present');
                }

                $lectureData[] = [
                    'id'         => $lecture->lecture_id,
                    'courseId'   => $course->course_id,
                    'courseCode' => $course->course_id,
                    'number'     => $lecture->lecture_no,
                    'name'       => $lecture->topic,
                    'date'       => $lecture->date, 
                    'attendance' => $attendance,
                ];
            }
        }

        return response()->json([
            'faculty' => [
                'name'     => $faculty->name,
                'position' => $faculty->position,
            ],
            'courses'  => $courseData,
            'lectures' => $lectureData,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store New Attendance
    |--------------------------------------------------------------------------
    */

    public function storeApi(
        Request $request,
        $courseId // Changed from `Course $course` to raw ID string/integer
    ) {
        $user = auth()->user();

        $faculty = Faculty::where('email_id', $user->email)->first();

        if (!$faculty) {
            return response()->json([
                'message' => 'Faculty record not found for this user.'
            ], 404);
        }

        // Check if course actually exists
        $course = DB::table('courses')->where('course_id', $courseId)->first();
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        */

        $this->authorizeCourse(
            $faculty->id,
            $courseId
        );

        /*
        |--------------------------------------------------------------------------
        | Validate request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'lecture_no'   => ['required', 'integer', 'min:1'],
            'topic'        => ['required', 'string', 'max:255'],
            'date'         => ['required', 'date'],
            'attendance'   => ['required', 'array'],
            'attendance.*' => ['required', 'boolean'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate lecture number
        |--------------------------------------------------------------------------
        */

        $lectureExists = DB::table('lectures')
            ->where('course_id', $courseId)
            ->where('lecture_no', $validated['lecture_no'])
            ->exists();

        if ($lectureExists) {
            return response()->json([
                'message' => 'This lecture number already exists for this course.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Get enrolled students
        |--------------------------------------------------------------------------
        */

        $studentIds = DB::table('enroll')
            ->where('course_id', $courseId)
            ->pluck('roll_no');

        $students = DB::table('students')
            ->whereIn('roll_no', $studentIds)
            ->get()
            ->keyBy('roll_no');

        /*
        |--------------------------------------------------------------------------
        | Verify submitted students
        |--------------------------------------------------------------------------
        */

        foreach (array_keys($validated['attendance']) as $rollNo) {
            if (!$students->has($rollNo)) {
                return response()->json([
                    'message' => 'Invalid student detected.'
                ], 403);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create lecture and attendance
        |--------------------------------------------------------------------------
        */

        $newLectureId = null;

        DB::transaction(function () use (
            $validated,
            $courseId,
            $faculty,
            $students,
            &$newLectureId
        ) {

            // Create lecture and get the auto-incremented ID
            $newLectureId = DB::table('lectures')->insertGetId([
                'course_id'  => $courseId,
                'faculty_id' => $faculty->id,
                'lecture_no' => $validated['lecture_no'],
                'topic'      => $validated['topic'],
                'date'       => $validated['date'],
            ]);

            // Prepare attendance array for bulk insert (much faster)
            $attendanceData = [];

            foreach ($students as $student) {
                $isPresent = $validated['attendance'][$student->roll_no] ?? false;

                $attendanceData[] = [
                    'lecture_id' => $newLectureId,
                    'roll_no'    => $student->roll_no,
                    'status'     => $isPresent ? 'present' : 'absent',
                ];
            }

            // Insert all attendances at once
            DB::table('attendance')->insert($attendanceData);
        });

        return response()->json([
            'message'    => 'Attendance saved successfully.',
            'lecture_id' => $newLectureId,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Existing Attendance
    |--------------------------------------------------------------------------
    */

    public function updateApi(
        Request $request,
        $lectureId // Changed from `Lecture $lecture` to raw ID string/integer
    ) {
        $user = auth()->user();

        $faculty = Faculty::where('email_id', $user->email)->first();

        if (!$faculty) {
            return response()->json([
                'message' => 'Faculty record not found for this user.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Load lecture and determine course
        |--------------------------------------------------------------------------
        */

        $lecture = DB::table('lectures')->where('lecture_id', $lectureId)->first();
        
        if (!$lecture) {
            return response()->json(['message' => 'Lecture not found.'], 404);
        }

        $courseId = $lecture->course_id;

        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        */

        $this->authorizeCourse(
            $faculty->id,
            $courseId
        );

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'topic'        => ['required', 'string', 'max:255'],
            'date'         => ['required', 'date'],
            'attendance'   => ['required', 'array'],
            'attendance.*' => ['required', 'boolean'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get enrolled students
        |--------------------------------------------------------------------------
        */

        $studentIds = DB::table('enroll')
            ->where('course_id', $courseId)
            ->pluck('roll_no');

        $students = DB::table('students')
            ->whereIn('roll_no', $studentIds)
            ->get()
            ->keyBy('roll_no');

        /*
        |--------------------------------------------------------------------------
        | Verify students
        |--------------------------------------------------------------------------
        */

        foreach (array_keys($validated['attendance']) as $rollNo) {
            if (!$students->has($rollNo)) {
                return response()->json([
                    'message' => 'Invalid student detected.'
                ], 403);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update lecture and attendance
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $lectureId,
            $students
        ) {

            // Update lecture
            DB::table('lectures')
                ->where('lecture_id', $lectureId)
                ->update([
                    'topic' => $validated['topic'],
                    'date'  => $validated['date'],
                ]);

            // Update every student's attendance
            foreach ($students as $student) {
                $isPresent = $validated['attendance'][$student->roll_no] ?? false;

                // updateOrInsert acts just like Eloquent's updateOrCreate
                DB::table('attendance')->updateOrInsert(
                    [
                        'lecture_id' => $lectureId,
                        'roll_no'    => $student->roll_no,
                    ],
                    [
                        'status' => $isPresent ? 'present' : 'absent',
                    ]
                );
            }
        });

        return response()->json([
            'message' => 'Attendance updated successfully.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Check whether faculty teaches the course
    |--------------------------------------------------------------------------
    */

    private function authorizeCourse(
        $facultyId,
        $courseId
    ): void {

        // Check the teach pivot table directly
        $teachesCourse = DB::table('teach')
            ->where('faculty_id', $facultyId)
            ->where('course_id', $courseId)
            ->exists();

        abort_unless(
            $teachesCourse,
            403,
            'You are not authorized to manage attendance for this course.'
        );
    }
}