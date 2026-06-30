<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Accept a filtered collection of students from the controller.
     * If none provided, falls back to all students.
     */
    public function __construct(private Collection $students)
    {
    }

    /**
     * Return the pre-filtered collection passed from the controller.
     */
    public function collection(): Collection
    {
        return $this->students;
    }

    /**
     * Define the column headings for the exported Excel file.
     */
    public function headings(): array
    {
        return [
            'Registration No',
            'Full Name',
            'Email',
            'Phone No',
            'Birthday',
            'Address',
        ];
    }

    /**
     * Map each student record to the corresponding columns.
     *
     * @param mixed $student
     */
    public function map($student): array
    {
        return [
            $student->reg_No,
            $student->Name,
            $student->email,
            $student->phone,
            $student->date_of_birth,
            $student->address,
        ];
    }

    /**
     * Apply styles to the header row — bold text and a blue background.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4'],
                ],
            ],
        ];
    }
}
