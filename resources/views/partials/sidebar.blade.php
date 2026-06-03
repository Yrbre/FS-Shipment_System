<aside x-data="{ hovered: false }"
    :class="{
        'w-[290px]': sidebarToggle || hovered,
        'w-[90px]': !sidebarToggle && !hovered
    }"
    @mouseenter="hovered = true" @mouseleave="hovered = false"
    class="sidebar fixed left-0 top-0 z-[9999] flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-gray-900 lg:static lg:translate-x-0">

    {{-- ===================== SIDEBAR HEADER ===================== --}}
    <div :class="(sidebarToggle || hovered) ? 'justify-between' : 'justify-center'"
        class="flex items-center gap-2 pb-7 pt-8">

        {{-- Logo full — tampil saat expanded atau hovered --}}
        <a href="{{ route('dashboard') }}" :class="(sidebarToggle || hovered) ? 'flex' : 'hidden'"
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

        {{-- Logo icon only — tampil saat collapsed dan tidak hovered --}}
        <a href="{{ route('dashboard') }}" :class="(!sidebarToggle && !hovered) ? 'flex' : 'hidden'"
            class="items-center justify-center">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3 6C3 4.34315 4.34315 3 6 3H18C19.6569 3 21 4.34315 21 6V18C21 19.6569 19.6569 21 18 21H6C4.34315 21 3 19.6569 3 18V6ZM6 8C6 7.44772 6.44772 7 7 7H17C17.5523 7 18 7.44772 18 8C18 8.55228 17.5523 9 17 9H7C6.44772 9 6 8.55228 6 8ZM6 12C6 11.4477 6.44772 11 7 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H7C6.44772 13 6 12.5523 6 12ZM6 16C6 15.4477 6.44772 15 7 15H12C12.5523 15 13 15.4477 13 16C13 16.5523 12.5523 17 12 17H7C6.44772 15.4477 6 16Z"
                        fill="white" />
                </svg>
            </div>
        </a>
    </div>
    {{-- ===================== END SIDEBAR HEADER ===================== --}}

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear h-full">
        <nav class="flex flex-col h-full" x-data="{ selected: '{{ request()->routeIs('dashboard') ? 'Dashboard' : (request()->is('master/*') ? 'Master' : (request()->is('shipments/*') ? 'Shipment' : (request()->is('imc/*') ? 'IMC' : (request()->is('tracking/*') ? 'Tracking' : 'Dashboard')))) }}' }">

            {{-- ===================== MAIN MENU ===================== --}}
            <div class="mb-6">
                <h3 class="mb-4 text-xs uppercase leading-5 text-gray-400 dark:text-gray-500">
                    <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'">Menu</span>
                    <svg :class="(!sidebarToggle && !hovered) ? 'block' : 'hidden'"
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
                            class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                            <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15Z"
                                    fill="currentColor" />
                            </svg>
                            <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'"
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
                                <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->is('master/*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                    viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.28636 3.71264C4.853 4.09212 4.5 4.60958 4.5 5.25V18.75C4.5 19.3904 4.853 19.9079 5.28636 20.2874C5.7212 20.6681 6.30718 20.9769 6.96654 21.2211C8.29107 21.7116 10.0708 22 12 22C13.9292 22 15.7089 21.7116 17.0335 21.2211C17.6928 20.9769 18.2788 20.6681 18.7136 20.2874C19.147 19.9079 19.5 19.3904 19.5 18.75V5.25C19.5 4.60958 19.147 4.09212 18.7136 3.71264C18.2788 3.33187 17.6928 3.02313 17.0335 2.77892C15.7089 2.28836 13.9292 2 12 2C10.0708 2 8.29107 2.28836 6.96654 2.77892C6.30718 3.02313 5.7212 3.33187 5.28636 3.71264ZM6.27454 4.84114C6.02476 5.05985 6 5.20007 6 5.25C6 5.29993 6.02476 5.44015 6.27454 5.65886C6.52284 5.87629 6.92537 6.10625 7.48751 6.31446C8.60601 6.72871 10.2013 7 12 7C13.7987 7 15.394 6.72871 16.5125 6.31446C17.0746 6.10625 17.4772 5.87629 17.7255 5.65886C17.9752 5.44015 18 5.29993 18 5.25C18 5.20007 17.9752 5.05985 17.7255 4.84114C17.4772 4.62371 17.0746 4.39375 16.5125 4.18554C15.394 3.77129 13.7987 3.5 12 3.5C10.2013 3.5 8.60601 3.77129 7.48751 4.18554C6.92537 4.39375 6.52284 4.62371 6.27454 4.84114ZM18 9.75V7.28202C17.7055 7.44688 17.3796 7.59287 17.0335 7.72108C15.7089 8.21164 13.9292 8.5 12 8.5C10.0708 8.5 8.29107 8.21164 6.96654 7.72108C6.62039 7.59287 6.29445 7.44688 6 7.28202V9.75C6 9.79993 6.02476 9.94015 6.27454 10.1589C6.52284 10.3763 6.92537 10.6063 7.48751 10.8145C8.60601 11.2287 10.2013 11.5 12 11.5C13.7987 11.5 15.394 11.2287 16.5125 10.8145C17.0746 10.6063 17.4772 10.3763 17.7255 10.1589C17.9752 9.94015 18 9.79993 18 9.75ZM6 11.782C6.29445 11.9469 6.62039 12.0929 6.96654 12.2211C8.29107 12.7116 10.0708 13 12 13C13.9292 13 15.7089 12.7116 17.0335 12.2211C17.3796 12.0929 17.7055 11.9469 18 11.782V14.25C18 14.2999 17.9752 14.4402 17.7255 14.6589C17.4772 14.8763 17.0746 15.1063 16.5125 15.3145C15.394 15.7287 13.7987 16 12 16C10.2013 16 8.60601 15.7287 7.48751 15.3145C6.92537 15.1063 6.52284 14.8763 6.27454 14.6589C6.02476 14.4402 6 14.2999 6 14.25V11.782ZM6 18.75V16.282C6.29445 16.4469 6.62039 16.5929 6.96654 16.7211C8.29107 17.2116 10.0708 17.5 12 17.5C13.9292 17.5 15.7089 17.2116 17.0335 16.7211C17.3796 16.5929 17.7055 16.4469 18 16.282V18.75C18 18.7999 17.9752 18.9401 17.7255 19.1589C17.4772 19.3763 17.0746 19.6063 16.5125 19.8145C15.394 20.2287 13.7987 20.5 12 20.5C10.2013 20.5 8.60601 20.2287 7.48751 19.8145C6.92537 19.6063 6.52284 19.3763 6.27454 19.1589C6.02476 18.9401 6 18.7999 6 18.75Z" fill="#343C54"/>
                                </svg>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'"
                                    class="flex-1 truncate text-left">Master Data</span>
                                <svg :class="{
                                    'rotate-180': selected === 'Master',
                                    'rotate-0': selected !== 'Master',
                                    'hidden': !sidebarToggle && !hovered,
                                    'block': sidebarToggle || hovered
                                }"
                                    class="h-4 w-4 flex-shrink-0 transition-transform duration-200 text-gray-400"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>

                            {{-- Dropdown Master — hanya tampil saat expanded atau hovered --}}
                            <div x-show="selected === 'Master' && (sidebarToggle || hovered)"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1" x-cloak
                                class="mt-1 ml-8 flex flex-col gap-0.5 border-l border-gray-100 pl-3 dark:border-gray-700">

                                @can('master.department.view')
                                    <a href="{{ route('master.departments.index') }}"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.departments.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Departemen
                                    </a>
                                @endcan

                                @can('master.warehouse.view')
                                    <a href="{{ route('master.warehouses.index') }}"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.warehouses.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Gudang
                                    </a>
                                @endcan

                                @can('master.supplier.view')
                                    <a href="{{ route('master.suppliers.index') }}"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.suppliers.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Supplier
                                    </a>
                                @endcan

                                @can('master.item.view')
                                    <a href="{{ route('master.items.index') }}"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.items.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Barang
                                    </a>
                                @endcan

                                @can('master.status.view')
                                    <a href="{{ route('master.statuses.index') }}"
                                        class="rounded-md px-3 py-2 text-sm transition-colors duration-150
                                        {{ request()->routeIs('master.statuses.*') ? 'text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                        Status Shipment
                                    </a>
                                @endcan

                                @can('master.user.view')
                                    <a href="{{ route('master.users.index') }}"
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
                    {{-- @can('shipment.view')
                        <li>
                            <a href="{{ route('shipments.index') }}"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('shipments.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->routeIs('shipments.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                    viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.25 6C3.25 4.75736 4.25736 3.75 5.5 3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V18C20.75 19.2426 19.7426 20.25 18.5 20.25H5.5C4.25736 20.25 3.25 19.2426 3.25 18V6ZM5.5 5.25C5.08579 5.25 4.75 5.58579 4.75 6V7.5H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H5.5ZM4.75 9H19.25V18C19.25 18.4142 18.9142 18.75 18.5 18.75H5.5C5.08579 18.75 4.75 18.4142 4.75 18V9ZM7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12ZM7.25 15C7.25 14.5858 7.58579 14.25 8 14.25H12C12.4142 14.25 12.75 14.5858 12.75 15C12.75 15.4142 12.4142 15.75 12 15.75H8C7.58579 15.75 7.25 15.4142 7.25 15Z"
                                        fill="currentColor" />
                                </svg>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'"
                                    class="truncate">Shipment</span>
                            </a>
                        </li>
                    @endcan --}}
                    {{-- ===== END SHIPMENT ===== --}}

                    {{-- ===== IMC VERIFIKASI ===== --}}
                    {{-- @can('imc.view')
                        <li>
                            <a href="{{ route('imc.index') }}"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('imc.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->routeIs('imc.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                    viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9 2.25C6.37665 2.25 4.25 4.37665 4.25 7C4.25 9.62335 6.37665 11.75 9 11.75C11.6234 11.75 13.75 9.62335 13.75 7C13.75 4.37665 11.6234 2.25 9 2.25ZM5.75 7C5.75 5.20507 7.20507 3.75 9 3.75C10.7949 3.75 12.25 5.20507 12.25 7C12.25 8.79493 10.7949 10.25 9 10.25C7.20507 10.25 5.75 8.79493 5.75 7ZM2.25 18C2.25 15.3766 4.37665 13.25 7 13.25H11C13.6234 13.25 15.75 15.3766 15.75 18V19.5C15.75 19.9142 15.4142 20.25 15 20.25H3C2.58579 20.25 2.25 19.9142 2.25 19.5V18ZM7 14.75C5.20507 14.75 3.75 16.2051 3.75 18V18.75H14.25V18C14.25 16.2051 12.7949 14.75 11 14.75H7Z"
                                        fill="currentColor" />
                                </svg>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'" class="truncate">IMC
                                    Verifikasi</span>
                            </a>
                        </li>
                    @endcan --}}
                    {{-- ===== END IMC VERIFIKASI ===== --}}

                    {{-- ===== TRACKING ===== --}}
                    {{-- @can('tracking.view')
                        <li>
                            <a href="{{ route('tracking.index') }}"
                                class="menu-item group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('tracking.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                                <svg class="h-5 w-5 flex-shrink-0
                                {{ request()->routeIs('tracking.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                                    viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 2.25C6.61522 2.25 2.25 6.61522 2.25 12C2.25 17.3848 6.61522 21.75 12 21.75C17.3848 21.75 21.75 17.3848 21.75 12C21.75 6.61522 17.3848 2.25 12 2.25ZM3.75 12C3.75 7.44365 7.44365 3.75 12 3.75C16.5563 3.75 20.25 7.44365 20.25 12C20.25 16.5563 16.5563 20.25 12 20.25C7.44365 20.25 3.75 16.5563 3.75 12ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.6893L15.0303 13.9697C15.3232 14.2626 15.3232 14.7374 15.0303 15.0303C14.7374 15.3232 14.2626 15.3232 13.9697 15.0303L11.4697 12.5303C11.329 12.3897 11.25 12.1989 11.25 12V8C11.25 7.58579 11.5858 7.25 12 7.25Z"
                                        fill="currentColor" />
                                </svg>
                                <span :class="(sidebarToggle || hovered) ? 'block' : 'hidden'" class="truncate">Tracking
                                    Shipment</span>
                            </a>
                        </li>
                    @endcan --}}
                    {{-- ===== END TRACKING ===== --}}

                </ul>
            </div>
            {{-- ===================== END MAIN MENU ===================== --}}

            {{-- ===================== USER INFO (bottom) ===================== --}}
            <div class="mt-auto pb-6">

                {{-- User card — tampil saat expanded atau hovered --}}
                <div :class="(sidebarToggle || hovered) ? 'flex' : 'hidden'"
                    class="items-center gap-3 rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-800">
                    <div
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
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

                {{-- Avatar only — tampil saat collapsed dan tidak hovered --}}
                <div :class="(!sidebarToggle && !hovered) ? 'flex' : 'hidden'" class="justify-center">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

            </div>
            {{-- ===================== END USER INFO ===================== --}}

        </nav>
    </div>
</aside>
