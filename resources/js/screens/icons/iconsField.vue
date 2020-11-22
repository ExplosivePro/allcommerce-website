<template>
    <div>
        <label>{{ label }}</label>

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text rend-icon"><i :class="renderedIcon"></i></span></div>
                <input class="form-control" type="text"
                       :name="name"
                       v-model="renderedText"
                       :placeholder="placeholder">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "iconsField",
    props: ['label', 'icon', 'iconName','name', 'value', 'placeholder'],
    watch: {
        renderedText(text) {
            if(text !== '') {
                let element = document.querySelector('.rend-icon i')
                let family = window.getComputedStyle( document.querySelector('.rend-icon i'), null ).getPropertyValue( 'font-family' )

                console.log(family);
                if(family.includes('Font Awesome') || family.includes('Line Awesome')) {
                    this.renderedIcon = text;
                }
                else {
                    this.renderedIcon = 'fad fa-times-circle';
                }
            }
            else {
                this.renderedIcon = 'fad fa-question-circle';
            }
        }
    },
    data() {
        return {
            renderedIcon: 'fad fa-question-circle',
            renderedText: '',
            classes: [],
        };
    },
    mounted() {
        if(this.icon !== '') {
            this.renderedIcon = this.icon;
        }

        if(this.value !== '') {
            this.renderedText = this.value;
        }
    }
}
</script>

<style scoped>

</style>
