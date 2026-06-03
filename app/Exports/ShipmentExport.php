<?php

namespace App\Exports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class ShipmentExport implements FromQuery,WithHeadings,WithMapping,ShouldAutoSize
{

public function __construct(private ?int $departmentId = null)
{

}


    public function query()
    {
        return Shipment::query()
        ->with(['supplier','department','status'])
        ->when($this->departmentId, fn($q) => $q->where('department_id', $this->departmentId))
        ->latest();
    }


    public function headings(): array
    {
        return ['No. PO', 'No. Invoice', 'No. BL', 'Supplier', 'Departemen', 'Status', 'ETD', 'ETA', 'Dibuat'];

    }

    public function map($row): array
    {
        return [
            $row->po,
            $row->no_invoice,
            $row->no_bl,
            $row->supplier->name ?? '-',
            $row->department->name ?? '-',
            $row->status->name ?? '-',
            $row->etd?->format('d/m/Y'),
            $row->eta?->format('d/m/Y'),
            $row->created_at->format('d/m/Y'),
        ];
    }

}
