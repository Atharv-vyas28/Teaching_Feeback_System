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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Collection $sessionIds,
        private readonly array $filters = []
    ) {}

    public function title(): string
    {
        return 'Attendance Report';
    }

    public function headings(): array
    {
        return [
            'Roll Number',
            'Student Name',
            'Department',
            'Course',
            'Course Code',
            'Date',
            'Session Topic',
            'Status',
            'Source',
            'Marked By',
            'Marked At',
        ];
    }

    public function collection(): Collection
    {
        if ($this->sessionIds->isEmpty()) {
            return collect();
        }

        return DB::table('attendance')
            ->join('users as students', 'attendance.student_id', '=', 'students.id')
            ->join('users as markers', 'attendance.marked_by', '=', 'markers.id')
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
            ->whereIn('attendance.class_session_id', $this->sessionIds)
            ->when(! empty($this->filters['search']), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('students.name', 'like', '%' . $this->filters['search'] . '%')
                        ->orWhere('students.roll_number', 'like', '%' . $this->filters['search'] . '%');
                });
            })
            ->when(! empty($this->filters['status']), function ($q) {
                $q->where('attendance.status', $this->filters['status']);
            })
            ->select([
                'students.roll_number',
                'students.name as student_name',
                'departments.name as department_name',
                'courses.name as course_name',
                'courses.code as course_code',
                'class_sessions.session_date',
                'class_sessions.topic',
                'attendance.status',
                'attendance.source',
                'markers.name as marked_by_name',
                'attendance.marked_at',
            ])
            ->orderBy('class_sessions.session_date')
            ->orderBy('students.name')
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->roll_number ?? '—',
            $row->student_name,
            $row->department_name ?? '—',
            $row->course_name,
            $row->course_code,
            $row->session_date,
            $row->topic ?? '—',
            ucfirst($row->status),
            ucfirst($row->source ?? 'regular'),
            $row->marked_by_name ?? '—',
            $row->marked_at
                ? \Carbon\Carbon::parse($row->marked_at)->format('d M Y, h:i A')
                : '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E40AF']],
            ],
        ];
    }
}
