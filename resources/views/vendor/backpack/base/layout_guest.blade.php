<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('backpack::inc.head')
</head>
<body class="hold-transition {{ config('backpack.base.skin') }}">
    <!-- Site wrapper -->
    <div class="container" id="app">
      <!-- Content Wrapper. Contains page content -->
      <div class="inner-container">

        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <section class="content">
            <div class="inner-content">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->

        <footer class="align-items-center" style="text-align: center;">
              @include('backpack::inc.footer')
      </footer>
      </div>
      <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')
    @stack('before_scripts')

    @include('backpack::inc.scripts')
    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>
</html>
