<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Department::query()->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'Code',
            'Name',
        ];
    }

    public function map($row):array
    {
        return [
            $row->code,
            $row->name,
        ];
    }


}
