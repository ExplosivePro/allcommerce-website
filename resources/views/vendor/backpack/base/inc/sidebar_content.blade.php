<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

@foreach(\App\Models\Utility\SidebarOptions::getSidebarOptions() as $nav_option)
    <li class="nav-item nav-dropdown">
        <button class="btn-transparent nav-link nav-dropdown-toggle text-left" style="width:100%">
            @if(!is_null($nav_option->icon))<i class="{!! $nav_option->icon !!}"></i>@endif{!!  $nav_option->name !!}
        </button>
    @switch($nav_option->menu_name)
        @case('Merchants')
            @include(backpack_view('inc.merchant_options'))
            @break
        @case('Shops')
            @include(backpack_view('inc.shop_options'))
            @break
        @default
            @include(backpack_view('inc.sidebar_suboptions_default'))
    @endswitch
</li>
@endforeach
