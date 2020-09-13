<template>
    <div class="grid-container">
        <div class="spinny-loader" v-if="loading === true">
            <div class="center-wrapper">
                <i class="fad fa-galaxy fa-spin"></i>
                <p>Loading...</p>
            </div>
        </div>
        <div class="viewer-content" v-else>
            <div class="no-items" v-if="items === ''">
                <h1>Invalid Permissions or No Abilities Available!</h1>
            </div>
            <div class="items-segment" v-else>
                <div class="inner-items-segment">
                    <div class="item-row" v-for="(item, name) in items">
                        <div class="inner-item">
                            <input type="checkbox" name="abilities[]" :value="name" v-model="item.checked" :disabled="item.disabled" @change="emitItemChecked(item, name)"/> <small>{{ item.title }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CheckboxGridComponent",
        props: ['items','loading'],
        methods: {
            emitItemChecked(item, name) {
                this.$emit('item-checked', item, name);
            }
        }
    }
</script>

<style scoped>
    @media screen {
        .grid-container {
            width: 100%;
            height: 100%;
        }

        .spinny-loader {
            text-align: center;
        }

        .spinny-loader i {
            font-size: 3em;
        }

        .spinny-loader p {
            margin-top: 0.5em;
            font-weight: thin;
            font-size: 1.25em;
            text-transform: uppercase;
            letter-spacing: 0.1em
        }

        .items-segment {
            width: 100%;
            height: 100%;
        }

        .inner-items-segment {

        }
    }
</style>
