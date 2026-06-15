<nav class="navbar navbar-expand-lg navbar-light bg-white flex-row border-bottom shadow">
    <div class="container-fluid">
        <a class="navbar-brand mx-lg-1 mr-0" href="{{ route('dashboard') }}">
            <img src="{{ asset('design/dark/assets/images/LogoTifico.png') }}" alt="Logo" style="width: 10vh;">
        </a>
        <button class="navbar-toggler mt-2 mr-auto toggle-sidebar text-muted">
            <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>
        <div class="navbar-slide bg-white ml-lg-4 justify-content-start" id="navbarSupportedContent">
            <a href="#" class="btn toggle-sidebar d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
                <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                </li>
                @can('master')
                <li class="nav-item dropdown">
                    <a href="#" id="ui-elementsDropdown" class="dropdown-toggle nav-link" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="ml-lg-2">Master</span>
                    </a>
                @endcan

                    <div class="dropdown-menu" aria-labelledby="ui-elementsDropdown">
                        @can('master.department.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.departments.index') }}"><span
                                    class="ml-1">Department</span></a>
                        @endcan

                        @can('master.item.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.items.index') }}"><span
                                    class="ml-1">Item</span></a>
                        @endcan

                        @can('master.status.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.statuses.index') }}"><span
                                    class="ml-1">Status</span></a>
                        @endcan

                        @can('master.supplier.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.suppliers.index') }}"><span
                                    class="ml-1">Supplier</span></a>
                        @endcan


                        @can('master.user.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.users.index') }}"><span
                                    class="ml-1">Users</span></a>
                        @endcan

                        @can('master.role.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.roles.index') }}"><span
                                    class="ml-1">Role</span></a>
                        @endcan

                        @can('master.warehouse.view')
                            <a class="nav-link pl-lg-2" href="{{ route('master.warehouses.index') }}"><span
                                    class="ml-1">Warehouse</span></a>
                        @endcan
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link pl-lg-3" href="#" id="appsDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Transaksi </a>
                    @can('shipment.view')
                        <ul class="dropdown-menu" aria-labelledby="appsDropdown">
                            <li class="nav-item">
                                <a class="nav-link pl-lg-2" href="{{ route('shipments.index') }}"><span class="ml-1">Shipment</span></a>
                            </li>
                        </ul>
                    @endcan
                    {{-- @can('admin')
                            <li class="nav-item dropdown">
                                <a class="dropdown-toggle nav-link pl-lg-2" href="#" id="contactDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="ml-1">Adjustment</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                                    <a class="nav-link pl-lg-2" href="#"><span
                                            class="ml-1">Adjustment
                                            Oil</span></a>
                                </ul>
                            </li>
                        @endcan --}}
                </li>
            </ul>
        </div>
        <form class="form-inline ml-md-auto d-none d-lg-flex text-muted">
        </form>
        <ul class="navbar-nav d-flex flex-row">
            <li class="nav-item dropdown ml-lg-0">
                <a class="nav-link dropdown-toggle text-muted" href="#" id="navbarDropdownMenuLink" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar avatar-sm mt-2">
                        <img src="{{ asset('design/dark/assets/avatars/face-1.jpg') }}" alt="..."
                            class="avatar-img rounded-circle">
                        <span class="ml-2">{{ auth()->user()->name }}</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="#">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
