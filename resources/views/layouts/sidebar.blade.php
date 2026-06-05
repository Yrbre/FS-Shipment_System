<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-center">
                <div class="logo ">
                    <a href="{{ route('dashboard') }}"><img
                            src="{{ asset('src/dist/assets/images/logo/LogoTifico.png') }}" alt="Logo" srcset=""
                            style="width: auto; height: auto;"></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item  ">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item @yield('menu-active')  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Master</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item @yield('active-departments')">
                            <a href="{{ route('master.departments.index') }}">Department</a>
                        </li>
                        <li class="submenu-item @yield('active-items')">
                            <a href="{{ route('master.items.index') }}">Item</a>
                        </li>
                        <li class="submenu-item @yield('active-statuses')">
                            <a href="{{ route('master.statuses.index') }}">Status</a>
                        </li>
                        <li class="submenu-item @yield('active-suppliers')">
                            <a href="{{ route('master.suppliers.index') }}">Supplier</a>
                        </li>
                        <li class="submenu-item @yield('active-warehouses')">
                            <a href="{{ route('master.warehouses.index') }}">Warehouse</a>
                        </li>
                        <li class="submenu-item @yield('active-users')">
                            <a href="{{ route('master.users.index') }}">User</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
