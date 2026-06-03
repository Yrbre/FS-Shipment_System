<?php
    namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return User::query()
        ->with(['department','roles'])
        ->latest();
    }

    public function headings(): array
    {
        return ['Nama', 'email','department','role','Dibuat'];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            $row->department->name ?? '-',
            $row->roles->pluck('name')->implode(', '),
            $row->created_at->format('d/m/Y'),
        ];
    }
}
