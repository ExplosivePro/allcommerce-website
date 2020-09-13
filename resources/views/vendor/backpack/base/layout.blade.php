<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('backpack::inc.head')
</head>
<body class="c-app {{ config('backpack.base.skin_theme') == 'dark' ? config('backpack.base.skin') : '' }}">
    <div id="app">
        <div class="c-sidebar c-sidebar-lg-show c-sidebar-fixed" id="sidebar">
            @include('backpack::inc.sidebar')
        </div>

        @if(config('backpack.base.use_right_sidebar'))
            @include('backpack::inc.right-sidebar')
        @endif

        <div class="c-wrapper">
            @include('backpack::inc.main_header')

            <div class="c-body">
                <main class="c-main">
                    <!-- Content Header (Page header) -->
                    @yield('header')

                    <!-- Main content -->
                    <section class="content">
                        @yield('content')
                    </section>
                    <!-- /.content -->
                </main>
            </div>
            <footer class="c-footer">
                @include('backpack::inc.footer')
            </footer>
        </div>
    </div>
    @yield('before_scripts')
    @stack('before_scripts')

    @include('backpack::inc.scripts')
    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')

    <script>
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
            event.preventDefault();
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                sessionStorage.setItem('sidebar-toggle-collapsed', '');
            } else {
                sessionStorage.setItem('sidebar-toggle-collapsed', '1');
            }
        });

        // Set active state on menu element
        var full_url = "{{ Request::fullUrl() }}";
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        // If not found, look for the link that starts with the url
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(full_url) || full_url.startsWith($(this).attr('href')); }
            );
        }

        $curentPageLink.parents('li').addClass('active');


    </script>
</body>
</html>
