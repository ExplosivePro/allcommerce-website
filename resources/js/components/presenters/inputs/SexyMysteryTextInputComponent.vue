<template>
    <input type="text" v-model="input" :placeholder="placeholder" />
</template>

<script>
export default {
    name: "SexyMysteryTextInputComponent",
    watch: {
        input(text) {
            /*
            if(text.length === 8) {
                text = text.replace('*','').replace('*','').replace('*','')
                    .replace(' ','').replace(' ','').replace(' ','')
            }

            this.codeTracker = text;

            switch(this.codeTracker.length) {
                case 1:
                    this.input = `${this.codeTracker} * * *`
                    break;

                case 2:
                    this.input = `${this.codeTracker.split(0, -1)} ${this.codeTracker.split(0,1)} * *`
                    break;

                case 3:
                    this.input = `${this.codeTracker.split(0, -1)} ${this.codeTracker.split(0, 1)} ${this.codeTracker.split(0,2)} *`
                    break;

                case 4:
                    this.input = `${this.codeTracker.split(0, -1)} ${this.codeTracker.split(0, -2)} ${this.codeTracker.split(0, -1)} ${this.codeTracker.split(0, -1)}`
                    break;

                default:
                    this.placeholder = '* * * *'
            }

             */
        }
    },
    data() {
        return {
            input: '',
            codeTracker: '',
            placeholder: '* * * *'
        }
    }
}
</script>

<style scoped>
input {
    width: 60%;
    margin-bottom: 0.5em;
    font-size: 2em;
    text-align: center;
    letter-spacing: 0.1em;
    padding: 2%;
}
</style>
