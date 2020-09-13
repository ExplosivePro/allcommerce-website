<template>
    <div class="c-sidebar c-sidebar-lg c-sidebar-light c-sidebar-right c-sidebar-overlaid" id="aside">
        <button class="c-sidebar-close c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-show" responsive="true">
            <i class="fal fa-times c-icon"></i>
        </button>

        <div class="nav-underline nav-underline-primary">
            <div class="">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab" aria-selected="true">
                            <i class="fad fa-list-ul c-icon icon-flip-horizontal"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#messages" role="tab" aria-selected="false">
                            <i class="fad fa-comment-alt-dots c-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#settings" role="tab" aria-selected="false">
                            <i class="fad fa-cog c-icon"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane active" id="timeline" role="tabpanel">
                <aside-context-tab></aside-context-tab>
            </div>

            <div class="tab-pane p-3" id="messages" role="tabpanel">
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                        <div class="c-avatar"><img class="c-avatar-img" :src="profileImg" :alt="'Profile Image of ' + username"></div>
                    </div>
                    <div><small class="text-muted">No Messages</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Your Messages Will Appear Here</div><small class="text-muted">Try back later!</small>
                </div>
                <hr>
                <!--
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                        <div class="c-avatar"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                        <div class="c-avatar"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                        <div class="c-avatar"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                        <div class="c-avatar"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                -->
            </div>

            <div class="tab-pane p-3" id="settings" role="tabpanel">
                <h6>Settings</h6>
                <!--
                <div class="c-aside-options">
                    <div class="clearfix mt-4"><small><b>Option 1</b></small>
                        <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                            <input class="c-switch-input" type="checkbox" checked=""><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                    </div>
                    <div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 2</b></small>
                        <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                            <input class="c-switch-input" type="checkbox"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                    </div>
                    <div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 3</b></small>
                        <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                            <input class="c-switch-input" type="checkbox"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                    </div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 4</b></small>
                        <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                            <input class="c-switch-input" type="checkbox" checked=""><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                        </label>
                    </div>
                </div>
                -->
                <hr>
                <!--
                <h6>System Utilization</h6>
                <div class="text-uppercase mb-1 mt-4"><small><b>CPU Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-muted">348 Processes. 1/4 Cores.</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>Memory Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-muted">11444GB/16384MB</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>SSD 1 Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-muted">243GB/256GB</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>SSD 2 Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div><small class="text-muted">25GB/256GB</small>
                -->
            </div>
        </div>
    </div>
</template>

<script>
import ContextTab from "./ContextTabComponent";
export default {
    name: "AsideBarComponent",
    props: ['username', 'profileImg']
}
</script>

<style scoped>

</style>
