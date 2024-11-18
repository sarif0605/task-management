<?php

namespace App\Exports;

use App\Models\ReportProject;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReportProjectsExport implements ToCollection, WithHeadingRow
{
    protected $deal_project_id;

    public function __construct($deal_project_id)
    {
        $this->deal_project_id = $deal_project_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validasi keberadaan kolom menggunakan has()
            if (!$row->has('mulai') || !$row->has('selesai') || !$row->has('status')) {
                Log::error('Baris tidak valid', $row->toArray());
                continue;
            }

            try {
                // Handle null values
                $startDate = !is_null($row['mulai']) ? $this->convertExcelDate($row['mulai']) : null;
                $endDate = !is_null($row['selesai']) ? $this->convertExcelDate($row['selesai']) : null;
                $status = strtolower($row['status']);

                // Validasi status
                if (in_array($status, ['plan', 'mulai', 'selesai', 'belum'])) {
                    ReportProject::create([
                        'deal_project_id' => $this->deal_project_id,
                        'pekerjaan' => $row['pekerjaan'] ?? null,
                        'status' => $status,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'bobot' => $row['bobot'] ?? 0.0,
                        'progress' => $row['progress'] ?? 0.0,
                        'durasi' => $row['durasi'] ?? 0.0,
                        'harian' => $row['harian'] ?? 0.0,
                    ]);
                    Log::info('Berhasil', ['status' => $status]);
                } else {
                    Log::error('Invalid status value', ['status' => $row['status'] ?? 'undefined']);
                }
            } catch (\Exception $e) {
                Log::error('Error parsing dates', [
                    'mulai' => $row['mulai'],
                    'selesai' => $row['selesai'],
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Mengonversi Excel serial date number menjadi format Y-m-d
     */
    private function convertExcelDate($date)
    {
        if (is_numeric($date)) {
            // 1 = 1900-01-01 di Excel
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($date - 2)->format('Y-m-d');
        }
        // Jika sudah dalam format string
        return Carbon::parse($date)->format('Y-m-d');
    }
}
