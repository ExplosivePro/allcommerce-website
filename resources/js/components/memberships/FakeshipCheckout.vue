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
    name: "FakeshipCheckout",
    props: ['membership'],
    watch: {},
    data() {
        return {
          selectedMembership: '',
          actionBtnClass: 'btn btn-danger',
          actionBtnText: 'Press this Button',
          resultSegmentText: 'Your order failed. Try again.'
        };
    },
    computed: {
      ...mapGetters({
        loading: 'membershipPurchase/getMembershipLoading',
        creditResult: 'membershipPurchase/getMembershipResult',
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
