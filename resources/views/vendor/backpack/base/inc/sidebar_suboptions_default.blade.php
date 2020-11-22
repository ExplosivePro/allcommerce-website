@if(count($sub_options = \App\Models\Utility\SidebarOptions::getSubSidebarOptions($nav_option->name)) > 0)
    <ul class="nav-dropdown-items">
        @foreach($sub_options as $sub_option)
            <li class="nav-item">
                <a class="nav-link" @if(!is_null($sub_option->route))href="{!! url($sub_option->route) !!}"@endif>
                    @if(!is_null($sub_option->icon))<i class="{!! $sub_option->icon !!}"></i>@endif{!!  $sub_option->name !!}
                    @if(strtotime($sub_option->created_at) > strtotime('now -3DAY'))<span class="badge badge-primary"> NEW! </span>@endif
                </a>
            </li>
        @endforeach
    </ul>
@endif
