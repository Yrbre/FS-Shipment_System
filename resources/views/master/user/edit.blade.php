@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Edit User</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('master.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Nama</label>
                                <input type="text" class="form-control uppercase @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Department</label>
                                <select class="form-control select2" id="simple-select2" name="department_id">
                                    <optgroup label="Select Department">
                                        <option value="" selected disabled>Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Role</label>
                                <select class="form-control select2" id="simple-select3" name="role_id">
                                    <optgroup label="Select Role">
                                        <option value="" selected disabled>Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()->id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('master.items.index') }}" class="btn btn-danger mr-3">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.getElementById('myForm').addEventListener('submit', function(e) {
                const btn = document.getElementById('submitBtn');

                if (btn.disabled) {
                    e.preventDefault();
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Loading...';
            });
        </script>

        {{-- Other Uom Input --}}
        <script>
            $(document).ready(function() {
                // 1. Inisialisasi Select2 dulu
                $('.select2').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#myForm')
                });

                $('.select3').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#myForm')
                });

                // 2. Baru daftarkan event listener
                $('#simple-select3').on('select2:select', function() {
                    if ($(this).val() === 'other') {
                        $('#otherUomInput').show();
                        $('#other_uom').attr('required', true);
                    } else {
                        $('#otherUomInput').hide();
                        $('#other_uom').attr('required', false);
                        $('#other_uom').val('');
                    }
                });
            });
        </script>
    @endpush
@endsection
