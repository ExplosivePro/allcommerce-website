<template>
    <div>
        <div class="loading" v-if="loading">
            <h1><i class="fad fa-money-bill-wave faa-passing animated" style="font-size: 4em; color: #42ba96"></i></h1>
            <br />
            <h3>Purchasing</h3>
            <h2 class="text-warning">{{ bundle.name }}!</h2>
        </div>
        <div v-else>
            <h3>{{ resultSegmentText }}</h3>
            <div class="card-footer">
                <button type="button" :class="actionBtnClass" v-on:click="closeExample('darkWithBlockingError')" v-html="actionBtnText"></button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: "FakewayCheckout",
    props: ['bundle'],
    watch: {
      creditResult(result) {
          // Failed scenario
          if((this.creditResult !== true) && (this.creditResult !== '')) {
              this.actionBtnText = '<i class="fad fa-ban"></i> Close Me.'

              if(this.statusCode === 418) {
                this.resultSegmentText = 'Your order failed. Go drink some tea.'
                this.actionBtnText = '<i class="fad fa-coffee-pot"></i> Gulp.'
              }
          }
          else {
              if(this.creditResult === true) {
                this.resultSegmentText = 'Nice Job You Scored Some Credits<br /> Click the button to start exploring!'
                this.actionBtnText = '<i class="fad fa-sack"></i> Close Me.'
                this.actionBtnClass = 'btn btn-success';
              }
          }
      }
    },
    data() {
      return {
          selectedBundle: '',
          actionBtnClass: 'btn btn-danger',
          actionBtnText: 'Press this Button',
          resultSegmentText: 'Your order failed. Try again.'
      };
    },
    computed: {
        ...mapGetters({
            loading: 'membershipPurchase/getLoading',
            creditResult: 'membershipPurchase/getCreditsResult',
            statusCode: 'membershipPurchase/getStatusCode'
        })
    },
    methods: {
        closeExample() {
          if(this.actionBtnClass === 'btn btn-success') {
              window.location.href = '/access/dashboard';
          }
          else {
            console.log('Closing!')
            this.$emit('close')
          }
        }
    },
    mounted() {

    }
}
</script>

<style scoped>

</style>
