<?php
// app/Exports/ItemExport.php
namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItemExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Item::query()->orderBy('name');
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'UOM', 'Deskripsi', 'Dibuat'];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->uom,
            $row->description,
            $row->created_at->format('d/m/Y'),
        ];
    }
}
