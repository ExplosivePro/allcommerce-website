<template>
    <div :class="selectName">
        <select :name="selectName" style="width: 100%" class="form-control select2_from_array" :class="selectName" :multiple="allowsMultiple === true" v-model="selectedItem">
            <option value="" v-if="(allowsNull !== false)" :selected="'' === selected">-</option>
            <option v-for="(val, col) in items" :value="col" :selected="col === selected">{{ val }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        name: "Select2FromArrayComponent",
        props: ['items', 'name', 'allowsMultiple', 'allowsNull', 'selected'],
        watch: {
            selectedItem(val) {
                this.$emit('item-selected', val);
                $('.'+this.selectName+' .select2-selection__rendered').text(this.items[val]);
            }
        },
        data() {
            return {
                selectedItem: ''
            }
        },
        computed: {
            selectName() {
                let name = this.name;

                if(this.allowsMultiple === true) {
                    name = name + '[]';
                }

                return name;
            }
        },
        methods: {
            init() {
                let _this = this;
                $('.'+this.selectName+' .select2_from_array').each(function (i, obj) {
                    if (!$(obj).hasClass("select2-hidden-accessible"))
                    {
                        $(obj).select2({
                            theme: "bootstrap",
                        }).on('select2:select', function(e) {
                            console.log('Selecting item - '+$(this).val());
                            _this.selectedItem = $(this).val();
                        })
                        .on('select2:unselect', function(e) {
                            console.log('unselecting item - '+$(this).val());
                                _this.selectedItem = '';
                        });
                    }

                    $('.'+this.selectName+' .select2-selection__rendered').text(_this.items[_this.selectedItem]);
                });
            }
        },
        mounted() {
            console.log('Select2FromArrayComponent mounted!', this.selected);
            if(this.selected !== '') {
                this.selectedItem = this.selected;
            }

            this.init();
        }
    }
</script>

<style scoped>

</style>
