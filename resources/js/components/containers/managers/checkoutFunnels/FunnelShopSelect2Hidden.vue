<template>
    <input type="hidden" :value="hVal" />
</template>

<script>
    import { mapMutations } from 'vuex';

    export default {
        name: "FunnelShopSelect2Hidden",
        props: ['name', 'value'],
        watch: {
            hVal(uuid) {
                this.setActiveShopUUID(uuid);
            }
        },
        data() {
            return {
                hVal: ''
            }
        },
        methods: {
            ...mapMutations({
                setActiveShopUUID: 'checkoutFunnelsManager/activeShop'
            }),
        },
        mounted() {
            console.log('FunnelShopSelect2 mounted!');
            let _this = this;

            if(this.value !== undefined) {
                this.hVal = this.value;
                this.setActiveShopUUID(this.value);
            }

            $(document).ready(function () {
                $(`select[name=${_this.name}]`).select2({
                    theme: 'bootstrap'
                }).on("select2:select", (e) => {
                    _this.hVal = e.target.value;
                }).on('select2:unselect', function(e) {
                    if ($(this).attr('multiple') && $(this).val().length === 0) {
                        $(this).val(null).trigger('change');
                    }
                });
            })
        }
    }
</script>

<style scoped>

</style>
