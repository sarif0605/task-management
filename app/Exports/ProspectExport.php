<?php

namespace App\Exports;

use App\Models\Prospect;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProspectExport implements ToCollection, WithHeadingRow
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (in_array($row['status'], ['prospek', 'survey', 'penawaran', 'deal'])) {
                Prospect::create([
                    'nama_produk' => $row['nama'],
                    'pemilik' => $row['pemilik'],
                    'lokasi' => $row['lokasi'],
                    'keterangan' => $row['keterangan'],
                    'status' => $row['status'] ?? 'prospek',
                    'tanggal' => $row['tanggal'] ?? now(),
                ]);
            } else {
                Log::error('Invalid status value', ['status' => $row['status']]);
            }
        }
    }
}
