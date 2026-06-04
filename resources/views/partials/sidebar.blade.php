{{-- Overlay backdrop untuk mobile --}}
<div
    x-show="sidebarToggle"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarToggle = false"
    class="fixed inset-0 z-[9998] bg-black/50 lg:hidden"
    x-cloak>
</div>

<aside
    x-data="{ hovered: false }"
    :class="{
        'translate-x-0': sidebarToggle,
        '-translate-x-full': !sidebarToggle,
        'lg:translate-x-0': true,
        'lg:w-[290px]': sidebarToggle || hovered,
        'lg:w-[90px]': !sidebarToggle && !hovered,
        'w-[290px]': true
    }"
    @mouseenter="hovered = true"
    @mouseleave="hovered = false"
    class="sidebar fixed left-0 top-0 z-[9999] flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-gray-900 lg:static lg:translate-x-0">

    {{-- ===================== SIDEBAR HEADER ===================== --}}
    <div
        :class="(sidebarToggle || hovered) ? 'justify-between' : 'lg:justify-center justify-between'"
        class="flex items-center gap-2 pb-7 pt-8">

        {{-- Logo full --}}
        <a href="{{ route('dashboard') }}"
            :class="(sidebarToggle || hovered) ? 'flex' : 'lg:hidden flex'"
            class="items-center gap-2">
            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3 6C3 4.34315 4.34315 3 6 3H18C19.6569 3 21 4.34315 21 6V18C21 19.6569 19.6569 21 18 21H6C4.34315 21 3 19.6569 3 18V6ZM6 8C6 7.44772 6.44772 7 7 7H17C17.5523 7 18 7.44772 18 8C18 8.55228 17.5523 9 17 9H7C6.44772 9 6 8.55228 6 8ZM6 12C6 11.4477 6.44772 11 7 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H7C6.44772 13 6 12.5523 6 12ZM6 16C6 15.4477 6.44772 15 7 15H12C12.5523 15 13 15.4477 13 16C13 16.5523 12.5523 17 12 17H7C6.44772 15.4477 6 16Z"
                        fill="white" />
                </svg>
            </div>
            <span class="text-sm font-bold text-gray-800 dark:text-white">Shipment MS</span>
        </a>

        {{-- Logo icon only — hanya di desktop collapsed --}}
        <a href="{{ route('dashboard') }}"
            :class="(!sidebarToggle && !hovered) ? 'lg:flex hidden' : 'hidden'"
            class="items-center justify-center">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3 6C3 4.34315 4.34315 3 6 3H18C19.6569 3 21 4.34315 21 6V18C21 19.6569 19.6569 21 18 21H6C4.34315 21 3 19.6569 3 18V6ZM6 8C6 7.44772 6.44772 7 7 7H17C17.5523 7 18 7.44772 18 8C18 8.55228 17.5523 9 17 9H7C6.44772 9 6 8.55228 6 8ZM6 12C6 11.4477 6.44772 11 7 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H7C6.44772 13 6 12.5523 6 12ZM6 16C6 15.4477 6.44772 15 7 15H12C12.5523 15 13 15.4477 13 16C13 16.5523 12.5523 17 12 17H7C6.44772 15.4477 6 16Z"
                        fill="white" />
                </svg>
            </div>
        </a>

        {{-- Close button — hanya di mobile --}}
        <button @click="sidebarToggle = false"
            class="lg:hidden flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
    {{-- ===================== END SIDEBAR HEADER ===================== --}}

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear h-full">
        <nav class="flex flex-col h-full"
            x-data="{ selected: '{{ request()->routeIs('dashboard') ? 'Dashboard' : (request()->is('master/*') ? 'Master' : (request()->is('shipments/*') ? 'Shipment' : (request()->is('imc/*') ? 'IMC' : (request()->is('tracking/*') ? 'Tracking' : 'Dashboard')))) }}' }">

            {{-- ===================== MAIN MENU ===================== --}}
            <div class="mb-6">
                <h3 class="mb-4 text-xs uppercase leading-5 text-gray-400 dark:text-gray-500">
                    <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'">Menu</span>
                    <svg :class="(!sidebarToggle && !hovered) ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current text-gray-400" width="24" height="24" viewBox="0 0 24 24">
                        <circle cx="5" cy="12" r="1.5" />
                        <circle cx="12" cy="12" r="1.5" />
                        <circle cx="19" cy="12" r="1.5" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-1">

                    {{-- ===== DASHBOARD ===== --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                            @click="if(window.innerWidth < 1024) sidebarToggle = false"
                            class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                            <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15Z"
                                    fill="currentColor" />
                            </svg>
                            <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'"
                                class="truncate">Dashboard</span>
                        </a>
                    </li>
                    {{-- ===== END DASHBOARD ===== --}}

                    {{-- ===== MASTER DATA ===== --}}
                    @canany(['master.department.view', 'master.warehouse.view', 'master.supplier.view',
                        'master.item.view', 'master.status.view', 'master.user.view'])
                        <li>
                            <button @click="selected = (selected === 'Master' ? '' : 'Master')"
                                class="menu-item group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->is('master/*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <i class="fas fa-database h-5 w-5 flex-shrink-0 text-center
                                    {{ request()->is('master/*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'"
                                    class="flex-1 truncate text-left">Master Data</span>
                                <i :class="{
                                        'rotate-180': selected === 'Master',
                                        'rotate-0': selected !== 'Master',
                                        'lg:block': sidebarToggle || hovered,
                                        'lg:hidden': !sidebarToggle && !hovered,
                                        'block': true
                                    }"
                                    class="fas fa-chevron-down text-xs transition-transform duration-200 text-gray-400">
                                </i>
                            </button>

                            {{-- Dropdown Master --}}
                            <div x-show="selected === 'Master' && (sidebarToggle || hovered || window.innerWidth < 1024)"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                x-cloak
                                class="mt-1 ml-8 flex flex-col gap-0.5 border-l border-gray-100 pl-3 dark:border-gray-700">

                                @can('master.department.view')
                                    <a href="{{ route('master.departments.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.departments.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Departemen
                                    </a>
                                @endcan

                                @can('master.warehouse.view')
                                    <a href="{{ route('master.warehouses.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.warehouses.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Gudang
                                    </a>
                                @endcan

                                @can('master.supplier.view')
                                    <a href="{{ route('master.suppliers.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.suppliers.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Supplier
                                    </a>
                                @endcan

                                @can('master.item.view')
                                    <a href="{{ route('master.items.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.items.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Barang
                                    </a>
                                @endcan

                                @can('master.status.view')
                                    <a href="{{ route('master.statuses.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.statuses.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Status Shipment
                                    </a>
                                @endcan

                                @can('master.user.view')
                                    <a href="{{ route('master.users.index') }}"
                                        @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.users.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Pengguna
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @endcanany
                    {{-- ===== END MASTER DATA ===== --}}

                    {{-- ===== SHIPMENT ===== --}}
                    @can('shipment.view')
                        <li>
                            <a href="{{ route('shipments.index') }}"
                                @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('shipments.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <i class="fas fa-truck h-5 w-5 flex-shrink-0 text-center
                                    {{ request()->routeIs('shipments.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'"
                                    class="truncate">Shipment</span>
                            </a>
                        </li>
                    @endcan
                    {{-- ===== END SHIPMENT ===== --}}

                    {{-- ===== IMC VERIFIKASI ===== --}}
                    @can('imc.view')
                        <li>
                            <a href="{{ route('imc.index') }}"
                                @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('imc.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <i class="fas fa-clipboard-check h-5 w-5 flex-shrink-0 text-center
                                    {{ request()->routeIs('imc.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'"
                                    class="truncate">IMC Verifikasi</span>
                            </a>
                        </li>
                    @endcan
                    {{-- ===== END IMC VERIFIKASI ===== --}}

                    {{-- ===== TRACKING ===== --}}
                    @can('tracking.view')
                        <li>
                            <a href="{{ route('tracking.index') }}"
                                @click="if(window.innerWidth < 1024) sidebarToggle = false"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('tracking.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <i class="fas fa-location-dot h-5 w-5 flex-shrink-0 text-center
                                    {{ request()->routeIs('tracking.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'lg:hidden block'"
                                    class="truncate">Tracking Shipment</span>
                            </a>
                        </li>
                    @endcan
                    {{-- ===== END TRACKING ===== --}}

                </ul>
            </div>
            {{-- ===================== END MAIN MENU ===================== --}}

            {{-- ===================== USER INFO (bottom) ===================== --}}
            <div class="mt-auto pb-6">

                {{-- User card — tampil saat expanded atau hovered atau mobile --}}
                <div :class="(sidebarToggle || hovered) ? 'flex' : 'lg:hidden flex'"
                    class="items-center gap-3 rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-800">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-800 dark:text-white">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="truncate text-xs text-gray-500">
                            {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                        </p>
                    </div>
                </div>

                {{-- Avatar only — hanya di desktop collapsed --}}
                <div :class="(!sidebarToggle && !hovered) ? 'lg:flex hidden' : 'hidden'" class="justify-center">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
            {{-- ===================== END USER INFO ===================== --}}

        </nav>
    </div>
</aside>
