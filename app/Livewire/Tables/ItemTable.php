<?php

namespace App\Livewire\Tables;

use App\Exports\ItemExport;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ItemTable extends DataTableComponent
{
    protected $model = Item::class;
    public int $rowNumber = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setPerPage(10)
            ->setSearchPlaceholder('Cari Item...')
            ->setSortingEnabled()
            ->setSearchEnabled()
            ->setEmptyMessage('Tidak ada data item yang ditemukan.')
            ->setThAttributes(fn (Column $column) => [
                'class' => 'px-4 py-3 text-left text-xs font-medium uppercase tracking-wider dark:text-gray-500 bg-gray-50 dark:bg-gray-800',
            ])
            ->setTdAttributes(fn (Column $column, $row, $columnIndex, $rowIndex) => [
                'class' => 'px-4 py-3 text-sm text-gray-700',
            ])
            ->setCurrentlyReorderingStatus(false);
    }

    public function columns(): array
    {
        return [

            Column::make('No')
            ->label(function($row) {
            static $counter = 0;
            $counter++;
            return $counter;
            }),

            Column::make('Kode', 'code')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => '<span class="font-mono text-xs">' . e($value) . '</span>')
                ->html(),

            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Uom', 'uom')
            ->sortable()
            ->searchable(),


            Column::make('Dibuat', 'created_at')
                ->sortable()
                ->format(fn ($value) => $value->format('d/m/Y')),

            Column::make('Aksi', 'id')
            ->excludeFromColumnSelect()
            ->unclickable()
            ->format(fn($value, $row, Column $column) => view('livewire.tables.partials.actions-master', [
            'editRoute'    => route('master.items.edit', $row->id),
            'deleteRoute'  => route('master.items.destroy', $row->id),
            'deleteFormId' => 'del-item-' . $row->id,
            'canEdit'      => Gate::allows('master.item.edit'),
            'canDelete'    => Gate::allows('master.item.delete'),
            ]))
            ->html(),
        ];

    }

    public function filters(): array
    {
        return [
            TextFilter::make('code')
            ->filter(fn(Builder $builder, string $value)=> $builder->where('code', 'like', '%'. $value . '%'))
        ];
    }

     public function bulkActions(): array
    {
    return [
        'exportExcel' => 'Export to Excel',
    ];
    }

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(new ItemExport, 'items_'.now()->format('Ymd'). '.xlsx') ;
    }
}
