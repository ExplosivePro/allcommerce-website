<div class="c-subheader justify-content-between px-3">

    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <li class="breadcrumb-item">AllCommerce</li>
        <!-- this is the Active Client -->
        @switch(request()->route()->uri())
            @case('access/sms-manager')
            @case('access/payment-gateways')
            <li class="breadcrumb-item"><a href="/switch/{!! $client->id !!}">{!! backpack_user()->getActiveClient() !!}</a></li>
            @break

            @default
            <li class="breadcrumb-item"><a href="/access/dashboard">{!! backpack_user()->getActiveClient() !!}</a></li>
        @endswitch

        <!-- the bread crumb loop  -->
        @switch(request()->route()->uri())
            @case('access/dashboard')
            <li class="breadcrumb-item active">Dashboard</li>
            @break

            @case('access/sms-manager')
            @if(!is_null($merchant))<li class="breadcrumb-item"><a class="text-capitalize">{{ $merchant->name }}</a></li>@endif

            <li class="breadcrumb-item active">SMS Manager</li>
            @break

            @case('access/payment-gateways')
            @if(!is_null($merchant))<li class="breadcrumb-item"><a class="text-capitalize">{{ $merchant->name }}</a></li>@endif

            <li class="breadcrumb-item active">Payment Gateways Manager</li>
            @break

            @case('access/shop/dashboard')
            <li class="breadcrumb-item"><a class="text-capitalize">{{ $merchant->name }}</a></li>
            <li class="breadcrumb-item"><a class="text-capitalize">{{ $shop->name }}</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            @break

            @default
            @if(isset($crud))
            <li class="breadcrumb-item"><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="breadcrumb-item active">{{ $active_bc }}</li>
            @else
                <li class="breadcrumb-item active">Unknown</li>
            @endif
        @endswitch
    </ol>

    <!-- @todo - do something REALLY awesome with this! -->
    <div class="c-subheader-nav d-md-down-none mfe-2">
{{--        <a class="c-subheader-nav-link" href="#">--}}
{{--            <i class="fad fa-chart-line c-icon" style="margin-right: 5%;"></i>  Dashboard--}}
{{--        </a>--}}
    </div>
</div>
