<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
  @if(request()->isSecure())
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  @endif
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="theme-color" content="#696cff" />

  <title>@yield('title') | TS </title>
  <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
  <!-- Toastr (local offline) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.min.css') }}">
  <!-- Select2 & jQuery (local offline) -->
  <link href="{{ asset('assets/vendor/libs/select2/select2.min.css') }}" rel="stylesheet" />
  <script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
  <!-- Include Styles -->
  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('layouts/sections/scriptsIncludes')
</head>

<body>
  

  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  <!-- Include Scripts -->
  @include('layouts/sections/scripts')
  


  @if (isset($errors) && $errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr !== 'undefined') {
          @foreach ($errors->all() as $error)
            toastr.error(@json($error));
          @endforeach
        }
      });
    </script>
  @endif

  <!--@if (session('success'))-->
  <!--  toastr.success('{{ session('success') }}');-->
  <!--@endif-->

</body>

</html>
