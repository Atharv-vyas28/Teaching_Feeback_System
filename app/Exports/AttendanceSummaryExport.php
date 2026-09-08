<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Summary sheet: one row per student per course with totals and percentage.
 */
class AttendanceSummarySheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(private readonly Collection $sessionIds) {}

    public function title(): string { return 'Summary'; }

    public function headings(): array
    {
        return [
            'Roll Number', 'Student Name', 'Department',
            'Course', 'Course Code',
            'Total Classes', 'Present', 'Absent', 'Attendance %',
        ];
    }

    public function collection(): Collection
    {
        if ($this->sessionIds->isEmpty()) return collect();

        return DB::table('attendance')
            ->join('users', 'attendance.student_id', '=', 'users.id')
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
            ->whereIn('attendance.class_session_id', $this->sessionIds)
            ->selectRaw("
                users.roll_number,
                users.name as student_name,
                departments.name as department_name,
                courses.name as course_name,
                courses.code as course_code,
                COUNT(attendance.id) as total_classes,
                SUM(CASE WHEN attendance.status IN ('present','late') THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->groupBy(
                'users.roll_number', 'users.name',
                'departments.name', 'courses.name', 'courses.code'
            )
            ->orderBy('courses.name')
            ->orderBy('users.name')
            ->get();
    }

    public function map($row): array
    {
        $pct = $row->total_classes > 0
            ? round(($row->present_count / $row->total_classes) * 100, 1)
            : 0;

        return [
            $row->roll_number ?? '—',
            $row->student_name,
            $row->department_name ?? '—',
            $row->course_name,
            $row->course_code,
            $row->total_classes,
            $row->present_count,
            $row->absent_count,
            $pct . '%',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF065F46']],
            ],
        ];
    }
}

/**
 * Two-sheet workbook: detailed records + summary.
 */
class AttendanceSummaryExport implements WithMultipleSheets
{
    public function __construct(
        private readonly Collection $sessionIds,
        private readonly array $filters = []
    ) {}

    public function sheets(): array
    {
        return [
            new AttendanceExport($this->sessionIds, $this->filters),
            new AttendanceSummarySheet($this->sessionIds),
        ];
    }
}
