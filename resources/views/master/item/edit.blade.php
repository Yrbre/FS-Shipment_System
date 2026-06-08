@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Edit Item</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('master.items.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Kode</label>
                                <input type="text" class="form-control uppercase @error('code') is-invalid @enderror"
                                    name="code" value="{{ old('code', $item->code) }}">
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $item->name) }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Uom</label>
                                <select class="form-control select2" id="simple-select3" name="uom">
                                    <optgroup label="Select Uom Type">
                                        <option value="" selected disabled>Select Uom</option>
                                        @foreach ($uom as $uomItem)
                                            <option value="{{ $uomItem }}" {{ old('uom', $item->uom) == $uomItem ? 'selected' : '' }}>{{ $uomItem }}</option>
                                        @endforeach
                                        <option value="other" {{ old('uom', $item->uom) == 'other' ? 'selected' : '' }}>Other</option>
                                    </optgroup>
                                </select>
                                @error('uom')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12" id="otherUomInput" style="display: none;">
                                <label for="other_uom">Specify Uom</label>
                                <input type="text" class="uppercase form-control" id="other_uom" name="other_uom"
                                    placeholder="Enter custom uom" value="{{ old('other_uom', $item->other_uom) }}">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Deskripsi</label>
                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $item->description) }}</textarea>
                                @error('description')
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
