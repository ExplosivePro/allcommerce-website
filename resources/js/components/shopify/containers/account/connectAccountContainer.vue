<template>
    <link-account
        :show-modal="showModal"
        :show-loading-wheel="loading"
        :show-error="error"
        :error-title="ajaxErrorTitle"
        :error-msg="ajaxErrorMsg"
        :icon="acIcon"
        @trigger-submit="triggerSubmit"
        @trigger-connect-modal="triggerConnectModal"
    ></link-account>
</template>

<script>
    export default {
        name: "connectAccountContainer",
        props: ['shop', 'acIcon'],
        data() {
            return {
                showModal: false,
                loading: false,
                error: false,
                ajaxErrorMsg: 'Default Message.',
                ajaxErrorTitle: 'Ooops.'
            };
        },
        methods: {
            triggerConnectModal(flag) {
                this.showModal = flag;
            },
            triggerSubmit(data) {

                this.error = false;
                this.ajaxLogInUser(data);
            },
            async ajaxLogInUser(data) {
                let _this = this;
                this.loading = true;

                data['shop'] = this.shop;

                $.ajax({
                    url: '/api/shopify/login',
                    method: 'POST',
                    data: data,
                    dataType: "json",
                    success(data) {
                        console.log('ajaxLogInUser Response - ', data);

                        if (('success' in data) ) {
                            if (data['success']) {

                            }
                            else {
                                _this.ajaxErrorTitle = 'Error - '+data.reason+'.';
                                _this.ajaxErrorMsg = data.msg;
                                _this.error = true;
                            }
                        }
                        else {
                            _this.ajaxErrorTitle = 'Error - Unknown Response From Server';
                            _this.ajaxErrorMsg = 'Maybe try again..?';
                            _this.error = true;
                        }

                        _this.loading = false;
                    },
                    error(error) {
                        _this.loading = false;
                        _this.ajaxErrorTitle = 'Error - Got a '+error.status+'.';
                        _this.ajaxErrorMsg = 'Yea that wasn\'t supposed to happen. oops. :\\';
                        _this.error = true;
                        console.log('ajaxLogInUser Error - ', error)
                    },
                });
            }
        },
        mounted() {}

    }
</script>

<style scoped>

</style>
