@if (backpack_auth()->check())
    <!-- Left side column. contains the sidebar -->
    <div class="c-sidebar-brand">
        <a href="{{ url('') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">{!! config('backpack.base.logo_mini') !!}</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{!! config('backpack.base.logo_lg') !!}</span>
        </a>
    </div>

    <ul class="c-sidebar-nav">
        @include('backpack::inc.sidebar_content')
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
@endif
