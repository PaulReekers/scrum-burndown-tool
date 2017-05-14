<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta name="twitter:url" content="{{ Request::url() }}">
    <meta name="og:title" content="@yield('title')" >
    <meta name="twitter:title" content="@yield('title')">
    <meta name="description" property="og:description" content="@yield('description')">
    <meta name="twitter:description" content="@yield('description')">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
