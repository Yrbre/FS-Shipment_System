<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Management</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @include('layouts.style')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        @include('layouts.navbar')
        <div id="main-content">
            @yield('content')
        </div>
    </div>
    @include('layouts.script')
</body>

</html>
