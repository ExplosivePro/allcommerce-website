<template>
    <div class="empty-set">
        <div class="inner-empty-set">
            <div class="statement">
                <div class="inner-statement">
                    <div class="statement-icon">
                        <div class="inner-icon">
                            <i class="fad fa-cow"></i>
                        </div>
                    </div>

                    <div class="statement-statement">
                        <div class="inner-statement-statement">
                            <h3>Stuff will Show up here on Pages that use this feature!</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--
        <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase c-small">Nothing Scheduled</div>

        <div class="list-group-item list-group-item-accent-warning list-group-item-divider">
            <div class="c-avatar float-right">
                <img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/4.jpg" alt="user@email.com">
            </div>

            <div>Meeting with <strong>Lucas</strong></div>
            <small class="text-muted mr-3"><i class="fad fa-calendar-alt c-icon"></i>&nbsp; 1 - 3pm</small>
            <small class="text-muted">
                <i class="fad fa-map-marker-smile c-icon"></i>&nbsp; Palo Alto, CA
            </small>
        </div>

        <div class="list-group-item list-group-item-accent-info">
            <div class="c-avatar float-right"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/4.jpg" alt="user@email.com"></div>

            <div>Skype with <strong>Megan</strong></div>
            <small class="text-muted mr-3"><i class="fad fa-calendar-alt c-icon"></i>&nbsp; 4 - 5pm</small>
            <small class="text-muted">
                <i class="fab fa-skype c-icon"></i>&nbsp; On-line
            </small>
        </div>

        <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase c-small">Tomorrow</div>

        <div class="list-group-item list-group-item-accent-danger list-group-item-divider">
            <div>New UI Project - <strong>deadline</strong></div>
            <small class="text-muted mr-3"><i class="fad fa-calendar-alt c-icon"></i>&nbsp; 10 - 11pm</small><small class="text-muted">
            <i class="fad fa-house c-icon"></i>&nbsp; creativeLabs HQ
        </small>

            <div class="c-avatars-stack mt-2">
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/2.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/3.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/4.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/5.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/6.jpg" alt="user@email.com"></div>
            </div>
        </div>

        <div class="list-group-item list-group-item-accent-success list-group-item-divider">
            <div><strong>#10 Startups.Garden</strong> Meetup</div>
            <small class="text-muted mr-3">
                <i class="fad fa-calendar-alt c-icon"></i>&nbsp; 1 - 3pm
            </small>
            <small class="text-muted">
                <i class="fad fa-map-marker-smile c-icon"></i>&nbsp; Palo Alto, CA
            </small>
        </div>

        <div class="list-group-item list-group-item-accent-primary list-group-item-divider">
            <div><strong>Team meeting</strong></div>
            <small class="text-muted mr-3">
                <i class="fad fa-calendar-alt c-icon"></i>&nbsp; 4 - 6pm
            </small><small class="text-muted">
            <i class="fad fa-house c-icon"></i>&nbsp; creativeLabs HQ
        </small>
            <div class="c-avatars-stack mt-2">
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/2.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/3.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/4.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/5.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/6.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/7.jpg" alt="user@email.com"></div>
                <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="https://coreui.io/vue/demo/3.0.0/dark/img/avatars/8.jpg" alt="user@email.com"></div>
            </div>
        </div>
        -->
    </div>
</template>

<script>
export default {
    name: "EmptyContextComponent"
}
</script>

<style scoped>
    @media screen {
        .empty-set {
            margin-top: 100%;
            width: 100%;
            height: 100%;
        }

        .inner-empty-set {
            margin: 0 5%;
        }

        .statement {
            width: 100%;
            height: 100%;
        }

        .inner-statement {
            text-align: center;
        }

        .inner-icon i {
            font-size: 3em;
        }
    }
</style>
