<template>
    <select :name="name" style="width: 100%" :class="classDec" :disabled="loading" v-model="selectedDrop">
        <option v-for="(lbl, val) in dropOptions" :value="val">{{lbl}}</option>
    </select>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    name: "ShopProductSelectComponent",
    props: ['name', 'classDec'],
    watch: {
        productOptions(options) {
            this.dropOptions = options;
            this.selectedDrop = '';
        }
    },
    data() {
        return {
            dropOptions: {
                '': 'Club Not Selected Yet'
            },
            selectedDrop: ''
        }
    },
    computed: {
        ...mapGetters({
            productOptions: 'checkoutFunnels/productOptions',
            loading: 'checkoutFunnels/loading'
        }),
    },
    methods: {
        ...mapActions({
            fetchProducts: 'checkoutFunnels/fetchShopProducts'
        }),
    },
    mounted() {
        let _this = this;
        console.log('Currently selected Shop -' + $('select[name=shop_id]').val());
        this.fetchProducts($('select[name=shop_id]').val())

        $('select[name=shop_id]').change(function (e) {
            console.log('Shop Id Changes -',e)
            _this.fetchProducts($('select[name=shop_id]').val());
        })
    }
}
</script>

<style scoped>

</style>
