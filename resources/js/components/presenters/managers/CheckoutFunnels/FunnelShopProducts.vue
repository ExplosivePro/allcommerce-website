<template>
    <div class="funnel-select">
        <div class="inner-funnel-select">
            <div class="not-ready" v-if="!ready">
                <h1 style="text-align: center;" v-if="!loading">Select a Shop, then products will be available.</h1>
                <div class="loading" v-else>
                    <sexy-hurricane-loader
                        loading-msg="Loading Published Products for Shop!"
                    ></sexy-hurricane-loader>
                </div>
            </div>
            <div class="select-section" v-show="ready">
                <div class="empty-set" v-if="(options === '') || (options.length === 0)">
                    <h1 style="text-align: center;">This shop has no products currently published.</h1>
                </div>

                <div class="filled-set" v-show="(options !== '') && (options.length > 0)">
                    <select
                        :id="name"
                        :name="name+'[]'"
                        style="width: 100%"
                        data-init-function="bpFieldInitSelect2FromArrayElement"
                        class="form-control select2_from_array"
                        v-model="selectedOption"
                        multiple
                        required
                        placeholder="Select a Shop"
                    >
                        <!-- <option value="">-</option> -->
                        <option v-for="(lbl, val) in curatedOptions(options)" :value="val">{{ lbl }}</option>
                    </select>

                    <div v-if="selectedOption.length > 0" class="variants">
                        <div class="inner-variants form-group">
                            <div class="variant-row form-group" v-for="(uuid, position) in selectedOption">
                                <label>Select the variants to {{ options[position].name }}</label>
                                <div v-if="options[position].variantOptions.length === 0">
                                    <h2><i>Ineligible. This Product has No Variants Available.</i></h2>
                                </div>
                                <div v-else>
                                    <div class="variant-subrow" v-for="(field, option) in options[position].variantOptions">
                                        <select
                                            :name="`variant[${uuid}][${option}]`"
                                            style="width: 100%"
                                            data-init-function="bpFieldInitSelect2FromArrayElement"
                                            class="form-control select2_from_array variant-option"
                                            required
                                            placeholder="Select a Shop"
                                        >
                                            <!-- <option value="">-</option> -->
                                            <option v-for="(lbl, val) in field" :value="lbl">{{ lbl }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import SexyHurricaneLoader from "../../widgets/loading/SexyHurricane";
    export default {
        name: "FunnelShopProducts",
        components: {
            SexyHurricaneLoader
        },
        props: ['name', 'ready', 'options', 'loading'],
        watch: {
            selectedOption(options) {
                console.log('Item(s)', options);

                let _this = this;
                setTimeout(function() {
                    _this.triggerVariantSelect2s();
                }, 500);
            }
        },
        data() {
            return {
                selectedOption: [],
                variantOptions: {}
            };
        },
        methods: {
            curatedOptions(options) {
                let results = {};

                for(let o in options) {
                     results[options[o].id] = options[o].name;
                }

                return results;
            },
            triggerVariantSelect2s() {
                $(`.variant-option`).select2({
                    theme: 'bootstrap'
                }).on("select2:select", (e) => {
                    //_this.selectedOption.push(e.params.data.id);
                    //console.log('selectedOption(s) - ', _this.selectedOption)
                }).on('select2:unselect', function(e) {
                    //console.log('Unselected... -',$(this).val())
                    //_this.selectedOption.pop(e.params.data.id);
                    // @todo - fucking do stuff here.
                });
            }
        },
        mounted() {
            let _this = this;
            $(document).ready(function () {
                //$(`select[name=${_this.name}]`).select2({
                $(`#${_this.name}`).select2({
                    theme: 'bootstrap'
                }).on("select2:select", (e) => {
                    _this.selectedOption.push(e.params.data.id);
                    console.log('selectedOption(s) - ', _this.selectedOption)
                }).on('select2:unselect', function(e) {
                    console.log('Unselected... -',$(this).val())
                    _this.selectedOption.pop(e.params.data.id);
                    // @todo - fucking do stuff here.
                });
            })
        }
    }
</script>

<style scoped>

</style>
