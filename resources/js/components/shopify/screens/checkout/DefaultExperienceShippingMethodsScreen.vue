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
                <div class="inner-toggled-shipping">
                    <slide-y-up-transition>
                        <div class="Polaris-Connected" v-for="(method, idx) in shippingMethods">
                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                <div class="Polaris-TextField" style="height: 5rem;">
                                    <label class="Polaris-TextField__Input" style="color: #000; z-index:100">
                                        <input type="radio" :value="idx" name="shippingMethod" v-model="selectedShippingMethod">
                                        <span style="padding-left: 1.5em;">
                                        <span>{{ method.title }} ${{ method.price }}</span>
                                    </span>
                                    </label>
                                    <div class="Polaris-TextField__Backdrop"></div>
                                </div>
                            </div>
                        </div>
                    </slide-y-up-transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DefaultExperienceShippingMethodsScreen",
    props: ['showShippingMethods', 'shippingMethods'],
    data() {
        return {
            selectedShippingMethod: ''
        }

        // @todo - when selectedShippingMethod changes, set the price through $emit
        // @todo - when $emit'd make the container update stuff in the proper modules to
        // @todo - update the shipping method, and total in the account order summary.
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
