<?php

namespace App\Exports;

use App\Models\Prospect;
use Illuminate\Support\Collection;
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
            Prospect::create([
                'name' => $rows['name'],
            'email' => $rows['email'],
            ]);
        }
    }
}
