<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    /** @var int Total rows successfully inserted */
    public int $importedCount = 0;

    /**
     * Process all rows from the uploaded Excel file.
     *
     * WithHeadingRow uses Str::slug($heading, '_') to format keys, so:
     *   "Full Name"  → "full_name"
     *   "Phone No"   → "phone_no"
     *   "Birthday"   → "birthday"
     *   "Address"    → "address"
     *   "Email"      → "email"
     */
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            // Fetch the latest student ONCE before the loop
            $latest = Student::query()
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            if ($latest && preg_match('/STU(\d+)/', $latest->reg_No, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
            } else {
                $nextNumber = 1;
            }

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2: row 1 is the header

                // --- Extract fields using slug-formatted keys ---
                $name    = $this->firstValue($row, ['full_name', 'name', 'fullname', 'student_name']);
                $email   = $this->firstValue($row, ['email', 'email_address']);
                $phone   = $this->firstValue($row, ['phone', 'phone_no', 'phone_number', 'mobile', 'mobile_no']);
                $bod     = $this->firstValue($row, ['birthday', 'date_of_birth', 'dob', 'birth_date']);
                $address = $this->firstValue($row, ['address', 'home_address']);

                // --- Required field check ---
                $missing = [];
                if (empty(trim((string) $name)))    $missing[] = 'Full Name';
                if (empty(trim((string) $email)))   $missing[] = 'Email';
                if (empty(trim((string) $phone)))   $missing[] = 'Phone';
                if (empty($bod))                     $missing[] = 'Birthday';
                if (empty(trim((string) $address))) $missing[] = 'Address';

                // Check if the entire row is completely empty (no data in any cell)
                $isEmptyRow = true;
                foreach ($row as $cellValue) {
                    if (!empty(trim((string)$cellValue))) {
                        $isEmptyRow = false;
                        break;
                    }
                }

                // If the row is completely empty, just skip it.
                if ($isEmptyRow) {
                    continue;
                }

                if (!empty($missing)) {
                    $missingStr = implode(', ', $missing);
                    throw new \Exception("Import Failed at row {$rowNum} - Missing fields: {$missingStr}");
                }

                // Cast to string and trim
                $name    = trim((string) $name);
                $email   = trim((string) $email);
                $phone   = trim((string) $phone);
                $address = trim((string) $address);

                // --- Validate email format ---
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Import Failed at row {$rowNum} - Invalid email format: '{$email}'");
                }

                // --- Skip duplicate email ---
                if (Student::where('email', $email)->exists()) {
                    // Skip the row instead of throwing an exception so the rest can import
                    continue;
                }

                // --- Parse date — handles Excel date serials and text dates ---
                try {
                    // Fix for common typo like YYYY-MM.DD or DD.MM.YYYY
                    if (is_string($bod)) {
                        $bod = str_replace('.', '-', $bod);
                    }
                    
                    // Excel sometimes passes dates as numeric serials (e.g. 44927)
                    if (is_numeric($bod)) {
                        $parsedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $bod)
                            ->format('Y-m-d');
                    } else {
                        $parsedDate = \Carbon\Carbon::parse((string) $bod)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Import Failed at row {$rowNum} - Invalid date format in row: {$bod}");
                }

                // --- Insert with auto-generated Reg No ---
                $regNo = 'STU' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                Student::create([
                    'reg_No'        => $regNo,
                    'Name'          => $name,
                    'email'         => $email,
                    'password'      => 'imported123',
                    'phone'         => $phone,
                    'date_of_birth' => $parsedDate,
                    'address'       => $address,
                ]);

                $nextNumber++;
                $this->importedCount++;
            }
        });
    }

    /**
     * Try multiple key names and return the first non-null value found.
     * This handles Excel files where users use slightly different column headers.
     */
    private function firstValue(mixed $row, array $keys): mixed
    {
        $isCollection = $row instanceof \Illuminate\Support\Collection;
        
        foreach ($keys as $key) {
            if ($isCollection) {
                if ($row->has($key) && !is_null($row->get($key)) && $row->get($key) !== '') {
                    return $row->get($key);
                }
            } else {
                if (array_key_exists($key, $row) && !is_null($row[$key]) && $row[$key] !== '') {
                    return $row[$key];
                }
            }
        }
        return null;
    }
}
