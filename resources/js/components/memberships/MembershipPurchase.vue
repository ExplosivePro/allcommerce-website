<template>
  <div v-if="activeScreen === 'members'">
    <carousel-checkout
        :content="content['members_content']"
        @membership="membershipSelected"
    ></carousel-checkout>

    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="modal" :title="legalModalTitle" hide-close-button blocking :enable-mobile-fullscreen="false">

      <sweet-modal-tab title="Legal" id="tab1" :disabled="acceptTerms">
        <div class="row legal">
          <div class="card" style="background: #182028;">
            <div class="card-body margin-auto derp2 text-light">
              <h4 id="list-item-1">Item 1</h4>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce consequat, purus at luctus pulvinar, sapien libero scelerisque neque, sed bibendum ex dui at felis. Integer sed imperdiet nibh, non eleifend lectus. Sed interdum, tortor et rutrum elementum, nisi risus venenatis risus, eget dictum ligula lacus et magna. Nullam pharetra enim quis velit pellentesque euismod. Integer nec neque massa. Maecenas sollicitudin id justo id varius. Maecenas vel nisl eget velit aliquam facilisis. Aenean luctus placerat ornare. Cras congue dolor et enim molestie rhoncus. Proin volutpat, augue eget luctus scelerisque, orci nisi mollis eros, ut volutpat ipsum justo ac tortor.</p>
              <h4 id="list-item-2">Item 2</h4>
              <p>Praesent imperdiet, nulla sit amet vehicula volutpat, dolor velit aliquet mauris, a placerat orci urna vitae tellus. In hac habitasse platea dictumst. Donec ut elit eu urna tristique pellentesque. Maecenas dignissim iaculis scelerisque. Aenean ligula neque, efficitur sed elementum id, posuere eget lectus. Sed tristique sollicitudin arcu at interdum. Donec eget gravida nisi, id mollis nibh. Aliquam id accumsan quam. Praesent at suscipit magna. Sed porttitor tincidunt congue. Fusce dignissim dapibus consectetur. Ut bibendum finibus sem eu sodales. Nullam porta nisi lacus, at mollis arcu malesuada at.</p>
              <h4 id="list-item-3">Item 3</h4>
              <p>Praesent imperdiet, nulla sit amet vehicula volutpat, dolor velit aliquet mauris, a placerat orci urna vitae tellus. In hac habitasse platea dictumst. Donec ut elit eu urna tristique pellentesque. Maecenas dignissim iaculis scelerisque. Aenean ligula neque, efficitur sed elementum id, posuere eget lectus. Sed tristique sollicitudin arcu at interdum. Donec eget gravida nisi, id mollis nibh. Aliquam id accumsan quam. Praesent at suscipit magna. Sed porttitor tincidunt congue. Fusce dignissim dapibus consectetur. Ut bibendum finibus sem eu sodales. Nullam porta nisi lacus, at mollis arcu malesuada at.</p>
              <h4 id="list-item-4">Item 4</h4>
              <p>Curabitur eget arcu quam. Nullam blandit justo eu commodo mollis. Quisque hendrerit orci vitae ante faucibus molestie. Praesent maximus et dolor eu condimentum. Praesent egestas est eu sem scelerisque tempor. Curabitur posuere metus vitae risus ultricies fermentum. Nulla fermentum, elit ac maximus laoreet, arcu massa bibendum ipsum, ac feugiat tellus orci id erat. Pellentesque elit felis, vehicula et egestas nec, pulvinar quis magna. Morbi sit amet malesuada erat, a placerat ex.</p>
            </div>
            <div class="card-footer">
                <div class="row text-center" style="justify-content: space-around;">
                    <button type="button" class="btn btn-warning" @click="acceptTermz()">I Accept</button>
                    <button type="button" class="btn btn-danger" @click="cancelOrder()">Cancel</button>
                </div>
            </div>
          </div>
        </div>
      </sweet-modal-tab>
      <sweet-modal-tab title="Purchase" id="Processing" :disabled="!(acceptTerms && processing && (!finished))">
        <div class="loading column legal justify-content-center text-center" v-if="loading">
          <h1><i class="fad fa-money-bill-wave faa-passing animated" style="font-size: 3em; color: #42ba96"></i></h1>
          <h3>Purchasing</h3>
          <h2 class="text-warning">{{ selectedMembership.name }}!</h2>
        </div>
        <div class="loading column legal text-center" style="padding-top: 5em;"v-else>
          <h3>{{ resultSegmentText }}</h3>
          <div class="card-footer">
            <button type="button" :class="actionBtnClass" v-on:click="closeExample('darkWithBlockingError')" v-html="actionBtnText"></button>
          </div>
        </div>
      </sweet-modal-tab>
      <sweet-modal-tab title="Review" id="Complete" :disabled="!(acceptTerms && processing && finished)">Accept Terms</sweet-modal-tab>
    </sweet-modal>
    <!--
    <sweet-modal modal-theme="dark" overlay-theme="dark" ref="modal" hide-close-button blocking :enable-mobile-fullscreen="false">
      <fakeship-checkout
          :membership="selectedMembership"
          @close="closeExample"
      ></fakeship-checkout>
    </sweet-modal>
    -->
    <sweet-modal modal-theme="dark" :overlay-theme="resultTheme" :icon="resultIcon" ref="response" :enable-mobile-fullscreen="false">
      {{ resultText }}
    </sweet-modal>

  </div>
</template>

<script>
  import CarouselCheckout from "../../screens/memberships/MembershipCarouselCheckout";
  import FakeshipCheckout from "./FakeshipCheckout";
  import { mapGetters, mapMutations, mapActions} from 'vuex';

  export default {
      name: "MembershipPurchase",
      components: {
        CarouselCheckout,
        FakeshipCheckout
      },
      props: ['content'],
      watch: {
        loading(flag) {
          console.log('loading status - '+ flag);

          if(flag) {
              this.resultIcon = 'success',
              this.resultTheme = 'light',
              this.resultText = 'Thank you for your purchase!'
              this.$refs.modal.open()
              this.purchaseMembership(this.selectedMembership);
          }
          else {
              if((this.membershipResult !== true) && (this.membershipResult !== '')) {
                  this.resultIcon = 'error'
                  this.resultTheme = 'dark'
                  this.resultText = this.membershipResult;
                  this.acceptTerms = false;
                  this.processing = false;

                  let _this = this;
                  this.$refs.response.open()
                  setTimeout(function () {
                    _this.$refs.response.close()
                  }, 3500);
              }
              else {
                  if(this.membershipResult === true){
                      let _this = this;
                      this.$refs.response.open()
                      this.finished = true;
                      this.resultSegmentText = 'Success.'
                      setTimeout(function () {
                        this.$refs.modal.open('Complete')
                        _this.$refs.response.close()
                      }, 3500);
                  }
              }
          }
        }
      },
      data() {
          return {
            legalModalTitle: 'Purchase Confirmation',
            selectedMembership: '',
            creditResponse: false,
            resultIcon: 'success',
            resultTheme: 'light',
            resultText: 'Thank you for your purchase!',
            noCreditsButtonText: '',
            acceptTerms: false,
            processing: false,
            finished: false,
            actionBtnClass: 'btn btn-danger',
            actionBtnText: 'Press this Button',
            resultSegmentText: 'Your order failed. Try again.'
          };
      },
      computed: {
          ...mapGetters({
            loading: 'membershipPurchase/getMembershipLoading',
            membershipResult: 'membershipPurchase/getMembershipResult',
            activeScreen: 'membershipPurchase/getActiveScreen'
        }),
      },
      methods: {
        initiatePaymentModal() {
          this.acceptTerms = false;
          this.processing = false;
          this.$refs.modal.open('tab1')
        },
        membershipSelected(membership) {
          console.log('Membership Selected', membership);

          if(!membership) {
            alert('Select a Membership')
          }
          else {
            this.selectedMembership = membership;
            this.initiatePaymentModal();
          }
        },
        closeExample() {
          console.log('Yes, Closing!')
          this.$refs.modal.close()
        },
        ...mapMutations({
          setLoading: 'membershipPurchase/membershipLoading',
          setScreen : 'membershipPurchase/activeScreen'
        }),
        ...mapActions({
          purchaseMembership: 'membershipPurchase/purchaseMembership'
        }),
        cancelOrder() {
            window.location.href = '/access/dashboard';
        },
        acceptTermz() {
            this.acceptTerms = true;
            this.processing = true;
            let _this = this;
            setTimeout(function () {
              console.log(_this.$refs.modal)
              _this.$refs.modal.open('Processing')
              _this.setLoading(true);
            }, 10)

        }
      },
      mounted() {

      }
  }
</script>

<style>
    @media screen {
       .sweet-modal .sweet-title > h2 {
         line-height: unset;
       }

      .legal {
        height: 20em;
        overflow: scroll;
      }
    }

    @media screen and (min-width: 768px) {
        .derp {
            width: 33.3333%;
            height: 16em;
            overflow: scroll;
        }

        .derp2 {
          width: 66.6666%;
          height: 20em;
          max-height: 20em;
          overflow: scroll;
        }
    }
</style>

<style scoped>
@media screen {
  .sweet-modal .sweet-title > h2 {
    line-height: unset;
  }
}


</style>
