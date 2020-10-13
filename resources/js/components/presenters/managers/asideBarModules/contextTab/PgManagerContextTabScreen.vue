<template>
    <div class="context-tab">
        <div class="inner-context-tab">
            <div class="view-mode-switch">
                <div class="inner-view-mode-switch">
                    <div class="switch-title">
                        <div class="inner-switch-title">
                            <p>Content Switch</p>
                        </div>
                    </div>
                    <div class="swatch">
                        <div class="inner-swatch">
                            <small>Reports</small>
                            <label class="c-switch c-switch-3d c-switch-primary">
                                <input type="checkbox" class="c-switch-input" v-model="contentSwitch" checked>
                                <span class="c-switch-slider"></span>
                            </label>
                            <small>Providers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PgManagerContextTabScreen",
        watch: {
            contentSwitch(flag) {
                this.$emit('content-switch', flag);
            }
        },
        data() {
            return {
                contentSwitch: true
            };
        }
    }
</script>

<style scoped>
    @media screen {
        .context-tab {
            height: 100%;
            width: 100%;
            border-top: 1px solid #000;
        }

        .inner-context-tab {
            display: flex;
            flex-flow: column;

            margin: 5% 0 0;
        }

        .view-mode-switch {
            height: 15%;
        }

        .switch-title {
            width: 100%;
        }

        .inner-switch-title {
            text-align: center;
        }

        .swatch {
            width: 100%;
            border-bottom: 1px solid #000;

        }

        .inner-swatch {
            display: flex;
            flex-flow: row;
            justify-content: space-evenly;
            align-items: center;
            margin: 1em 0;
        }
    }

    @media screen and (max-width: 999px) {}

    @media screen and (min-width: 1000px) {}
</style>
