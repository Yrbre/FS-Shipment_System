@extends('layouts.app')
@section('title', 'New Department')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create Department</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('master.departments.store') }}">
                        @csrf
                        @method('POST')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Kode</label>
                                <input type="text" class="form-control uppercase @error('code') is-invalid @enderror"
                                    name="code" value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('master.departments.index') }}" class="btn btn-danger mr-3">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div> <!-- /. card-body -->
            </div> <!-- /. card -->
        </div> <!-- /. col -->
    </div> <!-- /. end-section -->
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
@endsection
