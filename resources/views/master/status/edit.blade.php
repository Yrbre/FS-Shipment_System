@extends('layouts.app')
@section('title','Edit Status')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Edit Status</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('master.statuses.update', $status->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $status->name) }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('master.statuses.index') }}" class="btn btn-danger mr-3">Cancel</a>
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
