<template>
  <div class="col-sm-12 col-md-12 col-xl-8 margin-auto text-center">
    <label class="switch-label">Token Credits </label>
    <label class="switch switch-lg switch-3d switch-info">
      <input class="switch-input" type="checkbox" v-model="toggled" :checked="activeScreen === 'members'"><span class="switch-slider"></span>
    </label>
    <label class="switch-label">Memberships </label>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
        name: "TokenMembershipToggle",
        watch: {
            toggled(flag) {
                console.log('Screen toggled - ' +flag)
                if(flag) {
                    this.setScreen('members')
                }
                else {
                    this.setScreen('credits')
                }
            }
        },
        data() {
            return {
                toggled: true
            }
        },
        computed: {
          ...mapGetters({
            activeScreen: 'membershipPurchase/getActiveScreen'
          }),
        },
        methods: {
          ...mapMutations({
            setScreen : 'membershipPurchase/activeScreen'
          }),
        },
  }
</script>

<style scoped>
    @media screen {
        .switch {
            margin: 0 1.5em;
        }

      .switch-label {
          padding-bottom: 10px;
          width:  33%
      }

      .switch-slider {
          margin-top: 0.75em;
      }
    }
</style>
