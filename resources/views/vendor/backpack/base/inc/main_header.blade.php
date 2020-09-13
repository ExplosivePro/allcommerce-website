<header class="c-header c-header-fixed c-header-with-subheader">
    <!-- Left Sidebar Mobile Toggle Button -->
    <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show"><span class="c-header-toggler-icon"></span></button>
    <!-- Left Sidebar Middle Logo Icon -->
    <a class="c-header-brand a d-sm-none" href="#">
        <img class="c-header-brand img img-dark" src="https://amchorcms-assets.s3.amazonaws.com/anchorCMSLogo.png" width="50" height="46" alt="AllCommerce Logo">
        <img class="c-header-brand img img-light" src="https://amchorcms-assets.s3.amazonaws.com/Anchor CMS-blue.png" width="50" height="46" alt="AllCommerce Logo"></a>
    </a>
    <!-- Left Sidebar Normal Toggle Button -->
    <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>

    <!-- Header Left-side Navigation -->
    <ul class="c-header-nav d-md-down-none">
        <!-- <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Dashboard</a></li> -->
        <!-- <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Users</a></li> -->
        <!-- <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Settings</a></li> -->
    </ul>

    <!-- Ooooh, light and dark mode toggles -->
    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-3 c-d-legacy-none">
            <button class="c-class-toggler c-header-nav-btn" type="button" id="header-tooltip" data-target="body" data-class="c-dark-theme" data-toggle="c-tooltip" data-placement="bottom" title="" data-original-title="Toggle Light/Dark Mode">
                <i class="fad fa-moon c-icon c-d-dark-none"></i>
                <i class="fad fa-sun c-icon c-d-default-none"></i>
            </button>
        </li>
    </ul>

    <ul class="c-header-nav">
        @if(backpack_user()->can('receieve-notifications'))
            @include('backpack::inc.header-widgets.notifications')
        @endif

        @if(backpack_user()->can('be-assigned-tasks'))
            @include('backpack::inc.header-widgets.tasks')
        @endif

        @if(backpack_user()->can('receive-messages'))
            @include('backpack::inc.header-widgets.messages')
        @endif

        @include('backpack::inc.header-widgets.profile-pic')

        @if(config('backpack.base.use_right_sidebar'))
            <!-- Extra Side Bar Menu -->
            <button class="c-header-toggler c-class-toggler mfe-md-3" type="button" data-target="#aside" data-class="c-sidebar-show">
                <i class="fad fa-abacus c-icon c-icon-lg"></i>
            </button>
        @endif
    </ul>

    @include('backpack::inc.main_subheader')
</header>
