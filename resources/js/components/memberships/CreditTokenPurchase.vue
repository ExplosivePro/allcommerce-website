<template>
  <div v-if="activeScreen === 'credits'">
    <carousel-checkout
        :content="content.content"
        @bundle="bundleSelected"
    ></carousel-checkout>

    <div class="col-sm-6 col-md-10" style="margin: auto">
      <div class="card bg-light text-dark">
        <div class="card-body">
            <div class="text-center">
                <button type="button" class="btn btn-ghost-secondary text-dark" @click="bundleSelected(false)">
                  <i class="fad fa-credit-card-front"></i> {{ noCredsText }}
                </button>
            </div>
        </div>
      </div>
    </div>

    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="modal" hide-close-button blocking :enable-mobile-fullscreen="false">
        <fakeway-checkout
            :bundle="selectedBundle"
            @close="closeExample"
        ></fakeway-checkout>
    </sweet-modal>
    <sweet-modal modal-theme="dark" :overlay-theme="resultTheme" :icon="resultIcon" ref="response" :enable-mobile-fullscreen="false">
        {{ resultText }}
    </sweet-modal>
  </div>
</template>

<script>
    import CarouselCheckout from "../../screens/memberships/CreditCarouselCheckout";
    import FakewayCheckout from "./FakewayCheckout";

    import { mapGetters, mapMutations, mapActions} from 'vuex';

    export default {
        name: "CreditTokenPurchase",
        components: {
            CarouselCheckout,
            FakewayCheckout
        },
        props: ['content', 'role'],
        watch: {
            loading(flag) {
                console.log('loading status - '+ flag);
                if(flag) {
                    this.resultIcon = 'success',
                    this.resultTheme = 'light',
                    this.resultText = 'Thank you for your purchase!'
                    this.$refs.modal.open()
                    this.purchaseCredits(this.selectedBundle);
                }
                else {
                    // Failed scenario
                    if((this.creditResult !== true) && (this.creditResult !== '')) {
                        this.resultIcon = 'error'
                        this.resultTheme = 'dark'
                        this.resultText = this.creditResult;

                        let _this = this;
                        this.$refs.response.open()
                        setTimeout(function () {
                          _this.$refs.response.close()
                        }, 3500);
                    }
                    else {
                        if(this.creditResult === true){
                          let _this = this;
                          this.$refs.response.open()
                          setTimeout(function () {
                            _this.$refs.response.close()
                          }, 3500);
                        }
                    }
                }
            },
        },
        data() {
            return {
                selectedBundle: '',
                creditResponse: false,
                resultIcon: 'success',
                resultTheme: 'light',
                resultText: 'Thank you for your purchase!',
                noCreditsButtonText: ''
            };
        },
        computed: {
            ...mapGetters({
                loading: 'membershipPurchase/getLoading',
                creditResult: 'membershipPurchase/getCreditsResult',
                activeScreen: 'membershipPurchase/getActiveScreen'
            }),
            noCredsText() {
                if(this.role === 'guest') {
                    return 'Just Enter My Card Details and Go!';
                }
                else {
                    return 'No Thanks, Maybe Next time.'
                }
            },
        },
        methods: {
            initiatePaymentModal() {
              this.setLoading(true);
            },
            bundleSelected(bundle) {
              console.log('Bundle Selected', bundle);

              if(!bundle) {
                  if(this.role === 'guest') {
                    this.selectedBundle = bundle;
                    this.initiatePaymentModal();
                  }
                  else {
                      window.location.href = '/access/dashboard';
                  }
              }
              else {
                this.selectedBundle = bundle;
                this.initiatePaymentModal();
              }
            },
            closeExample() {
              console.log('Yes, Closing!')
              this.$refs.modal.close()
            },
            ...mapMutations({
                setLoading: 'membershipPurchase/loading',
                setScreen : 'membershipPurchase/activeScreen'
            }),
            ...mapActions({
                purchaseCredits: 'membershipPurchase/purchaseCredits'
            }),
        },
        mounted() {
            if(this.role === 'guest') {
                this.setScreen('credits')
            }

            console.log('CreditCarouselCheckout mounted!', this.role);
        }
    }
</script>

<style scoped>

</style>
