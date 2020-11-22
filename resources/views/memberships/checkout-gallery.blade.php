@if(!backpack_user()->isA('guest'))
    <token-membership-toggle></token-membership-toggle>
@endif

<credit-token-purchase
        :content="{{ json_encode($widget) }}"
        role="{!! backpack_user()->getRoles()[0] !!}"
></credit-token-purchase>

@if(!backpack_user()->isA('guest'))
    <membership-purchase
        :content="{{ json_encode($widget) }}"
    ></membership-purchase>
@endif
