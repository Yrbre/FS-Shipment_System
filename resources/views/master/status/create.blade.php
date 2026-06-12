@extends('layouts.app')
@section('title','New Status')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Form Create Status</strong>
                </div>
                <div class="card-body">
                    <form method="POST" id="myForm" action="{{ route('master.statuses.store') }}">
                        @csrf
                        @method('POST')
                        <div class="form-row">
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


        <script>
                $('.select2').select2({
                    theme: 'bootstrap4',
                });
        </script>
    @endpush
@endsection
