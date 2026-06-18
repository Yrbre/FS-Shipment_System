<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['id' => 1, 'code' => 'ACC', 'name' => 'Accounting'],
            ['id' => 2, 'code' => 'PURCH', 'name' => 'Purchasing'],
            ['id' => 3, 'code' => 'QARD', 'name' => 'Quality Assurance'],
            ['id' => 4, 'code' => 'BA/ESH', 'name' => 'Business Audit'],
            ['id' => 5, 'code' => 'SALES', 'name' => 'Marketing'],
            ['id' => 6, 'code' => 'HRD', 'name' => 'Human Resource'],
            ['id' => 7, 'code' => 'GA', 'name' => 'General Affair'],
            ['id' => 8, 'code' => 'CORSEC', 'name' => 'Corporate Secretary'],
            ['id' => 9, 'code' => 'ENG', 'name' => 'Engineering'],
            ['id' => 10, 'code' => 'IT', 'name' => 'Information And Technology'],
            ['id' => 11, 'code' => 'SF', 'name' => 'Staple Fiber'],
            ['id' => 12, 'code' => 'FY1', 'name' => 'Filament Yarn 1'],
            ['id' => 13, 'code' => 'FY2', 'name' => 'Filament Yarn 2'],
            ['id' => 14, 'code' => 'FY3', 'name' => 'Filament Yarn 3'],
            ['id' => 15, 'code' => 'PBX', 'name' => 'Polimer BX'],
            ['id' => 16, 'code' => 'PCP', 'name' => 'Polimer CP'],
            ['id' => 17, 'code' => 'UTY1', 'name' => 'Utility 1'],
            ['id' => 18, 'code' => 'UTY2', 'name' => 'Utility 2'],
        ]);
    }
}
