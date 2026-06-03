<?php
// app/Exports/WarehouseExport.php
namespace App\Exports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarehouseExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Warehouse::query()->orderBy('name');
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Dibuat'];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->created_at->format('d/m/Y'),
        ];
    }
}
