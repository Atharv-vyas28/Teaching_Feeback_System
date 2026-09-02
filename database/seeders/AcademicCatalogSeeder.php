<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $academicYear = DB::table('academic_years')
            ->where('name', '2025-26')
            ->first();

        if (! $academicYear) {
            $academicYearId = DB::table('academic_years')->insertGetId([
                'name' => '2025-26',
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_current' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $academicYearId = $academicYear->id;
        }

        $departments = [
            ['name' => 'Computer Science & Engineering', 'code' => 'CSE'],
            ['name' => 'Electrical Engineering', 'code' => 'EE'],
            ['name' => 'Mechanical Engineering', 'code' => 'ME'],
            ['name' => 'Civil Engineering', 'code' => 'CE'],
            ['name' => 'Physics', 'code' => 'PHY'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'Design', 'code' => 'DES'],
        ];

        $departmentIds = [];

        foreach ($departments as $department) {
            $existing = DB::table('departments')
                ->where('code', $department['code'])
                ->first();

            if (! $existing) {
                $departmentIds[$department['code']] =
                    DB::table('departments')->insertGetId([
                        'name' => $department['name'],
                        'code' => $department['code'],
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
            } else {
                $departmentIds[$department['code']] = $existing->id;
            }
        }

        /*
         * Starter program catalogue.
         * Add or rename programmes according to the official institute catalogue.
         */
        $programs = [
            ['department' => 'CSE', 'name' => 'B.Tech Computer Science & Engineering', 'code' => 'BTECH-CSE', 'years' => 4, 'type' => 'BTECH'],
            ['department' => 'EE', 'name' => 'B.Tech Electrical Engineering', 'code' => 'BTECH-EE', 'years' => 4, 'type' => 'BTECH'],
            ['department' => 'ME', 'name' => 'B.Tech Mechanical Engineering', 'code' => 'BTECH-ME', 'years' => 4, 'type' => 'BTECH'],
            ['department' => 'CE', 'name' => 'B.Tech Civil Engineering', 'code' => 'BTECH-CE', 'years' => 4, 'type' => 'BTECH'],

            ['department' => 'DES', 'name' => 'Bachelor of Design', 'code' => 'BDES', 'years' => 4, 'type' => 'BDES'],

            ['department' => 'CSE', 'name' => 'M.Tech Computer Science & Engineering', 'code' => 'MTECH-CSE', 'years' => 2, 'type' => 'MTECH'],
            ['department' => 'EE', 'name' => 'M.Tech Electrical Engineering', 'code' => 'MTECH-EE', 'years' => 2, 'type' => 'MTECH'],
            ['department' => 'ME', 'name' => 'M.Tech Mechanical Engineering', 'code' => 'MTECH-ME', 'years' => 2, 'type' => 'MTECH'],

            ['department' => 'PHY', 'name' => 'M.Sc Physics', 'code' => 'MSC-PHY', 'years' => 2, 'type' => 'MSC'],
            ['department' => 'CHEM', 'name' => 'M.Sc Chemistry', 'code' => 'MSC-CHEM', 'years' => 2, 'type' => 'MSC'],
            ['department' => 'MATH', 'name' => 'M.Sc Mathematics', 'code' => 'MSC-MATH', 'years' => 2, 'type' => 'MSC'],

            ['department' => 'CSE', 'name' => 'Ph.D Computer Science & Engineering', 'code' => 'PHD-CSE', 'years' => 5, 'type' => 'PHD'],
            ['department' => 'EE', 'name' => 'Ph.D Electrical Engineering', 'code' => 'PHD-EE', 'years' => 5, 'type' => 'PHD'],
            ['department' => 'ME', 'name' => 'Ph.D Mechanical Engineering', 'code' => 'PHD-ME', 'years' => 5, 'type' => 'PHD'],
            ['department' => 'PHY', 'name' => 'Ph.D Physics', 'code' => 'PHD-PHY', 'years' => 5, 'type' => 'PHD'],
            ['department' => 'CHEM', 'name' => 'Ph.D Chemistry', 'code' => 'PHD-CHEM', 'years' => 5, 'type' => 'PHD'],
            ['department' => 'MATH', 'name' => 'Ph.D Mathematics', 'code' => 'PHD-MATH', 'years' => 5, 'type' => 'PHD'],
        ];

        foreach ($programs as $program) {
            $existingProgram = DB::table('programs')
                ->where('code', $program['code'])
                ->first();

            if (! $existingProgram) {
                $programId = DB::table('programs')->insertGetId([
                    'department_id' => $departmentIds[$program['department']],
                    'name' => $program['name'],
                    'code' => $program['code'],
                    'duration_years' => $program['years'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $programId = $existingProgram->id;
            }

            $semesterCount = match ($program['type']) {
                'BTECH', 'BDES' => 8,
                'MTECH', 'MSC' => 4,
                'PHD' => 10,
                default => 2,
            };

            for ($number = 1; $number <= $semesterCount; $number++) {
                $semesterName = $program['type'] === 'PHD'
                    ? "Research Semester {$number} — {$program['code']}"
                    : "Semester {$number} — {$program['code']}";

                $exists = DB::table('semesters')
                    ->where('academic_year_id', $academicYearId)
                    ->where('program_id', $programId)
                    ->where('number', $number)
                    ->exists();

                if (! $exists) {
                    DB::table('semesters')->insert([
                        'academic_year_id' => $academicYearId,
                        'program_id' => $programId,
                        'name' => $semesterName,
                        'number' => $number,
                        'start_date' => '2025-07-01',
                        'end_date' => '2026-06-30',
                        'is_current' => false,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}