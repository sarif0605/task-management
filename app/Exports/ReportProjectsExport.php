<?php

namespace App\Exports;

use App\Models\ReportProject;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class ReportProjectsExport implements ToCollection, WithHeadingRow
{
    protected $deal_project_id;

    public function __construct($deal_project_id)
    {
        $this->deal_project_id = $deal_project_id;
    }

    public function collection(Collection $rows)
    {
        $dataToInsert = [];

        foreach ($rows as $index => $row) {
            $startDate = !empty($row['mulai']) ? $this->convertExcelDate($row['mulai']) : null;
            $endDate = !empty($row['selesai']) ? $this->convertExcelDate($row['selesai']) : null;
            $status = strtolower($row['status'] ?? '');

            if (in_array($status, ['plan', 'mulai', 'selesai', 'belum'])) {
                Log::info('Processing row', [
                    'row_number' => $index + 1,
                    'pekerjaan' => $row['pekerjaan'] ?? null,
                    'status' => $row['status'] ?? null,
                ]);
                $dataToInsert[] = [
                    'id' => Str::uuid(),
                    'deal_project_id' => $this->deal_project_id,
                    'pekerjaan' => $row['pekerjaan'] ?? null,
                    'status' => $status,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'bobot' => $row['bobot'] ?? 0.0,
                    'progress' => $row['progress'] ?? 0.0,
                    'durasi' => $row['durasi'] ?? 0.0,
                    'harian' => $row['harian'] ?? 0.0,
                    'excel_row_number' => $index + 1, // Menyimpan urutan asli dari Excel
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($dataToInsert)) {
            ReportProject::insert($dataToInsert);
        }
    }

    /**
     * Mengonversi Excel serial date number menjadi format Y-m-d
     */
    private function convertExcelDate($date)
    {
        try {
            if (is_numeric($date)) {
                return Carbon::createFromFormat('Y-m-d', '1900-01-01')
                    ->addDays($date - 2)
                    ->format('Y-m-d');
            }
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Error converting date', ['date' => $date, 'error' => $e->getMessage()]);
            return null;
        }
    }
}
