<?php

namespace App\Providers;

use App\Models\ShipmentHistory;
use App\Repositories\Eloquent\DepartmentRepository;
use App\Repositories\Eloquent\ImcVerifRepository;
use App\Repositories\Eloquent\ItemRepository;
use App\Repositories\Eloquent\ShipmentHistoryRepository;
use App\Repositories\Eloquent\ShipmentItemRepository;
use App\Repositories\Eloquent\StatusRepository;
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Eloquent\WarehouseRepository;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Repositories\Interfaces\ImcVerifRepositoryInterface;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use App\Repositories\Interfaces\ShipmentHistoryRepositoryInterface;
use App\Repositories\Interfaces\ShipmentItemRepositoryInterface;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use App\Repositories\Interfaces\StatusRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(ShipmentRepositoryInterface::class, ShipmentHistory::class);
        $this->app->bind(ShipmentItemRepositoryInterface::class, ShipmentItemRepository::class);
        $this->app->bind(ShipmentHistoryRepositoryInterface::class, ShipmentHistoryRepository::class);
        $this->app->bind(ImcVerifRepositoryInterface::class, ImcVerifRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
