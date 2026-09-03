<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Kipanya' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased kipanya-platform">
{{ $slot ?? '' }}@yield('content')
</body>
</html>
