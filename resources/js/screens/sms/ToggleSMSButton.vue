<template>
    <button type="submit" class="btn" :class="button" data-style="zoom-in"><span class="ladda-label"><i class="la la-mobile"></i> {{ action }} {{ entityName }} Feature</span></button>
</template>

<script>
export default {
    name: "ToggleSMSButton",
    props: ['button', 'action', 'entityName', 'enabled'],
    mounted() {
        let _this = this;
        $(document).ready(function() {
            console.log('Enabled '+_this.enabled);
            if(_this.enabled === 0) {
                setTimeout(function () {
                    console.log('Table Access No Allowed');
                    $('#crudTable_wrapper').slideUp();
                }, 1500)
            }

        });
    }
}
</script>

<style scoped>

</style>
