<template>
    <form class="inner-select" method="POST" :action="url">
        <div class="select-row" v-for="(val, key) in options">
            <div class="inner-row">
                <button type="button" @click="selectedVal = key">{{ val }}</button>
            </div>
        </div>
        <input type="hidden" name="selected_val" :value="selectedVal" />
        <input type="hidden" name="_token" :value="csrf">
    </form>
</template>

<script>
    export default {
        name: "SelectToPostGridComponent",
        props: ['options', 'url'],
        watch: {
            selectedVal(val) {
                setTimeout(function() {
                    $('.inner-select').submit();
                }, 100);
            }
        },
        data() {
            return {
                selectedVal: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    }
</script>

<style scoped>

</style>
