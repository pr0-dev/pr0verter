<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
@include('layouts.navigation')

<!-- Page Heading -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            Subnav ka
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <form action="{{route('convertUpload')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="file" name="video">
            <input type="number" name="size">
            <input type="number" name="audio">
            <input type="checkbox" name="resolution">
            <input type="number" name="start">
            <input type="number" name="end">
            <input type="submit" value="submit">

            @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
        </form>
    </main>
</div>
</body>
</html>
