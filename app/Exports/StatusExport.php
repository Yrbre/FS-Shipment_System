<?php
namespace App\Exports;

use App\Models\Status;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StatusExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Status::query()->orderBy('name');
    }

    public function headings(): array
    {
        return ['Nama', 'Dibuat'];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->created_at->format('d/m/Y'),
        ];
    }
}
