<li class="c-header-nav-item dropdown">
    <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <div class="c-avatar"><img class="c-avatar-img" src="{!! backpack_user()->profile_img !!}" alt="Profile Image of {!! backpack_user()->username !!}"></div>
    </a>
    <div class="dropdown-menu dropdown-menu-right pt-0">
        <!--
        <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
        <a class="dropdown-item" href="#">
            <i class="fal fa-bell c-icon mfe-2"></i> Updates<span class="badge badge-info mfs-auto">42</span>
        </a>
        <a class="dropdown-item" href="#">
            <i class="fal fa-envelope-open-text c-icon mfe-2"></i> Messages<span class="badge badge-success mfs-auto">42</span>
        </a>
        <a class="dropdown-item" href="#">
            <i class="fal fa-clipboard-list-check c-icon mfe-2"></i> Tasks<span class="badge badge-danger mfs-auto">42</span>
        </a>
        <a class="dropdown-item" href="#">
            <i class="fal fa-comment-alt c-icon mfe-2"></i> Comments<span class="badge badge-warning mfs-auto">42</span>
        </a>
        -->
        <div class="dropdown-header bg-light py-2"><strong>Settings</strong></div>
        <a class="dropdown-item" href="#">
            <i class="fal fa-user c-icon mfe-2"></i> Profile
        </a>
        <a class="dropdown-item" href="#">
            <i class="fal fa-cog c-icon mfe-2"></i> Settings
        </a>
        <!--
        <a class="dropdown-item" href="#">
            <i class="fal fa-credit-card c-icon mfe-2"></i> Payments<span class="badge badge-secondary mfs-auto">42</span>
        </a>
        <a class="dropdown-item" href="#">
            <i class="fal fa-file c-icon mfe-2"></i> Projects<span class="badge badge-primary mfs-auto">42</span>
        </a>
        -->
        <div class="dropdown-divider"></div>
        <!--
        <a class="dropdown-item" href="#">
            <i class="fal fa-lock c-icon mfe-2"></i> Lock Account
        </a>
        -->
        <a class="dropdown-item" href="{!! backpack_url() !!}/logout">
            <i class="fad fa-portal-exit fa-flip c-icon mfe-2"></i> Logout
        </a>
    </div>
</li>
