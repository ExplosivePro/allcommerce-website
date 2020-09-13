<template>
    <button-tank
        :loading="loading"
        :error="error"
        :buttons="buttons"
        @mega-click="emitSelected"
    ></button-tank>
</template>

<script>
    import ButtonTank from '../../../presenters/widgets/mega-buttons/ComponentsOfMegaButtons';
    import { mapGetters,  mapActions } from 'vuex'
    export default {
        name: "MegaButtonContainer",
        components: {
            ButtonTank
        },
        data() {
            return {
                loading: true,
                error: '',
                buttons: [],
            }
        },
        computed: {
            ...mapGetters({
                widgets: 'dashboard/widgets',
            }),
        },
        methods: {
            ...mapActions({
                triggerRightWidgetSwap: 'dashboard/triggerRightWidgetSwap'
            }),
            emitSelected(clickId) {
                console.log('Checking out widgets', this.widgets);
                this.triggerRightWidgetSwap(clickId);
            },
            retrieveWidgetButtons() {
                let mc_btn = {
                    text: 'Email Reports',
                    image: 'https://amchorcms-assets.s3.amazonaws.com/Daco_2054005.png',
                    id: "7a977872-5825-4a9a-bf96-4e4d754db20a"
                };

                this.buttons.push(mc_btn);

                let fb_btn = {
                    text: 'Social Media Reports',
                    image: 'https://amchorcms-assets.s3.amazonaws.com/kindpng_324348.png',
                    id: "bb21c2aa-2eb2-4ae0-80e8-583b4466f458"
                };
                this.buttons.push(fb_btn);

                let google_btn = {
                    text: 'Google SEM Reports',
                    image: 'https://amchorcms-assets.s3.amazonaws.com/Google-Adwords-Icon-01.png',
                    id: "5e7f6e06-0abf-4b65-b26c-bc9f1e8c8ea9"
                };
                this.buttons.push(google_btn);

                let kpi_btn = {
                    text: 'Performance Reports',
                    image: 'https://amchorcms-assets.s3.amazonaws.com/kissclipart-statistics-clipart-computer-icons-chart-statistics-0939c5c52324c727.png',
                    id: "1be5d918-56e6-4b9e-96d2-14e4684dffca"
                };
                this.buttons.push(kpi_btn);

                this.error = '';
                this.loading = false;
            }
        },
        mounted() {
            this.retrieveWidgetButtons();
            console.log('MegaButtonContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
