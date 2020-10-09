<template>
    <div class="shipping-method-panel">
        <div class="inner-shipping-method-panel inner-form-segment">
            <div class="untoggled-shipping" v-if="!showShippingMethods">
                <div class="inner-untoggled-shipping">
                    <div class="Polaris-Connected">
                        <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField" style="height: 5rem;">
                                <label id="PolarisTextField7" class="Polaris-TextField__Input" style="color: #000">
                                    <i class="fad fa-truck fa-flip-horizontal"></i>
                                    <span style="padding-left: 1.5em;">
                                        <span>Enter your shipping address to see shipping options</span>
                                    </span>
                                </label>
                                <div class="Polaris-TextField__Backdrop"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="toggled-shipping" v-else>
                    <div class="inner-toggled-shipping" :style="'display:none'">
                        <div class="Polaris-Connected">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField shipping-fields" style="height: 5rem;" v-for="(method, idx) in shippingMethods">
                                    <label class="Polaris-TextField__Input" style="color: #000; z-index:100; display: flex; flex-flow: row;">
                                        <input type="radio" :value="idx" name="shippingMethod" v-model="selectedShippingMethod">
                                        <span style="padding-left: 1.5em; display: flex; flex-flow: row; justify-content: space-between; width: 100%;">
                                            <span><b>{{ method.title }}</b></span><span> ${{ method.price }}</span>
                                    </span>
                                    </label>
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DefaultExperienceShippingMethodsScreen",
    props: ['showShippingMethods', 'shippingMethods'],
    watch: {
        showShippingMethods(flag) {
            console.log('showShippingMethods updated to '+ flag);

            this.toggle();
        },
        shippingMethods(methods) {
            console.log('Passing Shipping methods to the view - ', methods);
        },
        selectedShippingMethod(idx) {
            console.log('Shipping Method Selected - ', this.shippingMethods[idx]);

            this.$emit('selected', idx);
        }
    },
    data() {
        return {
            selectedShippingMethod: ''
        }

        // @todo - when selectedShippingMethod changes, set the price through $emit
        // @todo - when $emit'd make the container update stuff in the proper modules to
        // @todo - update the shipping method, and total in the account order summary.
    },
    methods: {
        toggle() {
            let _this = this;
            setTimeout(function () {
                if(_this.showShippingMethods) {
                    console.log('Sliding down')
                    $('.inner-toggled-shipping').slideDown();
                }
                else {
                    console.log('Sliding up')
                    $('.inner-toggled-shipping').slideUp();
                }
            }, 100);
        }
    },
    mounted() {
        this.toggle();
    }
}
</script>

<style scoped>
    @media screen {

    }

    @media screen and (max-width: 999px) {
        .inner-form-segment {
            padding: 1% 0 5%;
        }
    }

    @media screen and (min-width: 1000px) {
        .inner-form-segment {
            padding: 1% 0 5%;
        }
    }
</style>
