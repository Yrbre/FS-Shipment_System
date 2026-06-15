<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('design/dark/assets/images/LogoTifico.png') }}">
    <title>@yield('title')</title>
    <!--CSS -->
    @include('layouts.style')
    @stack('style')
</head>

<body class="horizontal dark  ">
    <div class="wrapper">
        @include('layouts.navbar')
        <main role="main" class="main-content">
            <div class="container-fluid">
                @yield('content')
            </div> <!-- .container-fluid -->
        </main> <!-- main -->
    </div> <!-- .wrapper -->
    @include('layouts.script')
    <script>
        @if (session('success'))
            Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                theme: 'dark',
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                didClose: () => {
                    document.body.classList.remove('swal2-shown');
                    document.body.classList.remove('swal2-height-auto');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            }).fire({
                icon: "success",
                title: '{{ session('success') }}'
            });
        @endif
    </script>

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                theme: 'dark',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                didClose: () => {
                    document.body.classList.remove('swal2-shown');
                    document.body.classList.remove('swal2-height-auto');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            });
        @endif
    </script>

    {{-- Fix global: bersihkan sisa Bootstrap modal backdrop --}}
    <script>
        $(document).on('hidden.bs.modal', function() {
            if ($('.modal.show').length === 0) {
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                $('.modal-backdrop').remove();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
