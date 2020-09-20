<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <!-- JavaScripts -->
  @yield('scripts')

  <!-- Styles -->
  @yield('styles')
</head>

<body>
  <main>
    @yield('content')
  </main>
</body>
