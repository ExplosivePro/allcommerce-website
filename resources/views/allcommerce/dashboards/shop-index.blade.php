<div class="shop-dashboard">
    <div class="inner-shop-dashboard">
        <shop-dashboard
                :client="{{ json_encode($config['args']['client']->toArray()) }}"
                :merchant="{{ json_encode($config['args']['merchant']->toArray()) }}"
                :shop="{{ json_encode($config['args']['shop']->toArray()) }}"
        ></shop-dashboard>
    </div>
</div>
