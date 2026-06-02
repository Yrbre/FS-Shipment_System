{{-- resources/views/layouts/sidebar.blade.php --}}
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col" x-data="{ open: true }">
    <div class="px-6 py-5 border-b border-gray-100">
        <span class="font-bold text-blue-600 text-lg">Shipment MS</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1 text-sm">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Dashboard
        </a>

        {{-- Master Data — Admin only --}}
        @canany(['master.department.view', 'master.warehouse.view', 'master.supplier.view', 'master.item.view',
            'master.status.view', 'master.user.view'])
            <div x-data="{ open: {{ request()->is('master/*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                    <span>Master Data</span>
                    <span x-text="open ? '▲' : '▼'" class="text-xs"></span>
                </button>
                <div x-show="open" x-cloak class="ml-3 mt-1 space-y-1">
                    @can('master.department.view')
                        <a href="{{ route('master.departments.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.departments.*') ? 'text-blue-600 font-medium' : '' }}">Departemen</a>
                    @endcan
                    @can('master.warehouse.view')
                        <a href="{{ route('master.warehouses.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.warehouses.*') ? 'text-blue-600 font-medium' : '' }}">Gudang</a>
                    @endcan
                    @can('master.supplier.view')
                        <a href="{{ route('master.suppliers.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.suppliers.*') ? 'text-blue-600 font-medium' : '' }}">Supplier</a>
                    @endcan
                    @can('master.item.view')
                        <a href="{{ route('master.items.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.items.*') ? 'text-blue-600 font-medium' : '' }}">Barang</a>
                    @endcan
                    @can('master.status.view')
                        <a href="{{ route('master.statuses.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.statuses.*') ? 'text-blue-600 font-medium' : '' }}">Status
                            Shipment</a>
                    @endcan
                    @can('master.user.view')
                        <a href="{{ route('master.users.index') }}"
                            class="block px-3 py-1.5 rounded text-gray-500 hover:bg-gray-50 {{ request()->routeIs('master.users.*') ? 'text-blue-600 font-medium' : '' }}">Pengguna</a>
                    @endcan
                </div>
            </div>
        @endcanany

        @can('shipment.view')
            <a href="{{ route('shipments.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('shipments.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Shipment
            </a>
        @endcan

        @can('imc.view')
            <a href="{{ route('imc.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('imc.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                IMC Verifikasi
            </a>
        @endcan

        @can('tracking.view')
            <a href="{{ route('tracking.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('tracking.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Tracking Shipment
            </a>
        @endcan

    </nav>
</aside>
