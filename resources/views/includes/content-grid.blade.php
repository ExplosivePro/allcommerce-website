<div class="inner-card">
    <div class="control-panel">
        <div class="inner-control-panel">
            <div class="page-title-segment">
                <div class="inner-page-title">
                    <h2 class="page-title">{!! $params['name'] !!}</h2>
                </div>
            </div>
            <div class="action-panel-segment">
                <div class="inner-action-panel">
                    @if(array_key_exists('action_panel_buttons', $params))
                        @foreach($params['action_panel_buttons'] as $action_button)
                            <div class="action-button">
                                <div class="inner-action-button">
                                    <button type="button" onclick="alert('Not Available')">{!! $action_button['name'] !!}</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="button-panel-segment">
                <div class="inner-button-panel">
                    @if(array_key_exists('button_panel_buttons', $params))
                        @foreach($params['button_panel_buttons'] as $panel_button)
                            <div class="panel-button">
                                <div class="inner-panel-button">
                                    <button type="button" onclick="alert('Not Available')">{!! $panel_button['name'] !!}</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="content-grid">
        <div class="outer-content-grid">
            <div class="inner-content-grid">
                <div class="grid-filter-bar">
                    <div class="inner-grid-filter-bar">
                        <ul class="filter-bar-list">
                            @foreach($params['grid_filters'] as $grid_filter)
                            <li class="grid-filter-item">
                                <a id="{!! $grid_filter['id'] !!}" class="filter-button">
                                    <span>{!! $grid_filter['name'] !!}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <inventory-grid></inventory-grid>
            </div>
        </div>
    </div>
</div>
