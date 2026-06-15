@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>
                <a href="{{ route('master.roles.index') }}" class="text-secondary text-decoration-none">Roles</a>
                <span class="text-secondary mx-1">/</span>
                <span class="text-primary">Create Role</span>
            </span>
        </div>

        <form action="{{ route('master.roles.store') }}" method="POST" id="myForm">
            @csrf

            {{-- Section 1: Role Name --}}
            <section class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-primary me-1"></i>
                        <h5 class="mb-0 text-white">Role Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-white">
                                    Role Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Admin, Manager, Viewer">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Section 2: Permissions --}}
<section class="mb-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-key text-primary mr-2"></i>
                <h5 class="mb-0 text-white">Permissions</h5>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-light mr-2" id="btn-check-all">
                    <i class="fa-solid fa-check-double mr-1"></i> Check All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-uncheck-all">
                    <i class="fa-solid fa-xmark mr-1"></i> Uncheck All
                </button>
            </div>
        </div>
        <div class="card-body p-4">

            @error('permissions')
                <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
            @enderror

            <div class="row">
                @foreach ($permissions as $group => $groupPermissions)
                    @php
                        $label = match($group) {
                            'imc'               => 'IMC',
                            'shipment'          => 'Shipment',
                            'tracking'          => 'Tracking',
                            'master'            => 'Master',
                            'master.department' => 'Master — Department',
                            'master.item'       => 'Master — Item',
                            'master.role'       => 'Master — Role',
                            'master.status'     => 'Master — Status',
                            'master.supplier'   => 'Master — Supplier',
                            'master.user'       => 'Master — User',
                            'master.warehouse'  => 'Master — Warehouse',
                            default             => ucwords(str_replace('.', ' — ', $group)),
                        };

                        $order  = ['view' => 1, 'create' => 2, 'edit' => 3, 'delete' => 4, 'verify' => 5];
                        $sorted = $groupPermissions->sortBy(fn($p) => $order[last(explode('.', $p->name))] ?? 99);
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card border h-100" style="border-color: rgba(255,255,255,0.1) !important;">

                            {{-- Card Header --}}
                            <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
                                style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span class="text-white font-weight-semibold" style="font-size:0.85rem;">
                                    {{ $label }}
                                </span>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body py-3 px-3">
                                @foreach ($sorted as $permission)
                                    @php
                                        $action = last(explode('.', $permission->name));
                                        $icon   = match($action) {
                                            'view'   => 'fa-eye text-info',
                                            'create' => 'fa-plus text-success',
                                            'edit'   => 'fa-pen text-warning',
                                            'delete' => 'fa-trash text-danger',
                                            'verify' => 'fa-circle-check text-primary',
                                            default  => 'fa-circle text-secondary',
                                        };
                                        $permId = 'perm_' . str_replace('.', '_', $permission->name);
                                    @endphp
                                    <div class="form-check mb-2" style="padding-left: 1.5rem;">
                                        <input class="form-check-input permission-checkbox"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            id="{{ $permId }}"
                                            data-group="{{ $group }}"
                                            style="cursor:pointer;"
                                            {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white"
                                            for="{{ $permId }}"
                                            style="cursor:pointer; font-size:0.875rem;">
                                            <i class="fa-solid {{ $icon }} mr-1" style="font-size:0.75rem;"></i>
                                            {{ ucfirst($action) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mb-4">

                <a href="{{ route('master.roles.index') }}" class="btn btn-secondary mr-2">Cancel</a>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Role
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
        document.getElementById('myForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');

            if (btn.disabled) {
                e.preventDefault(); // cegah submit kedua
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Loading...';
        });
    </script>
<script>
(function () {

    // ── Toggle per group (switch) ─────────────────────────────────
    document.querySelectorAll('.btn-toggle-group').forEach(function (toggle) {
        const group = toggle.dataset.group;

        // Sync state switch dengan checkbox di dalamnya
        const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);

        toggle.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Sync switch saat salah satu checkbox berubah
        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {
                const allChecked = [...checkboxes].every(c => c.checked);
                const someChecked = [...checkboxes].some(c => c.checked);
                toggle.checked = allChecked;
                toggle.indeterminate = someChecked && !allChecked;
            });
        });
    });

    // ── Check All ─────────────────────────────────────────────────
    document.getElementById('btn-check-all').addEventListener('click', function () {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        document.querySelectorAll('.btn-toggle-group').forEach(sw => {
            sw.checked = true;
            sw.indeterminate = false;
        });
    });

    // ── Uncheck All ───────────────────────────────────────────────
    document.getElementById('btn-uncheck-all').addEventListener('click', function () {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.btn-toggle-group').forEach(sw => {
            sw.checked = false;
            sw.indeterminate = false;
        });
    });

})();
</script>
@endpush
