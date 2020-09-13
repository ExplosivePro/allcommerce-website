<template>
  <div class="dashboard">
    <div class="loading inner-dashboard" v-if="loading">
        <div class="loading-row">
            <h1>Loading{{ ' '+ shopType + ', ' }}Hang on...</h1>
        </div>
    </div>
    <div class="inner-dashboard" v-else>
      <!-- Top Row -->
      <div class="widget-row top-row-shop-widgets">
        <div class="inner-widgets-row"></div>
      </div>

      <!-- Middle-Top Row -->
      <div class="widget-row middle-top-row-shop-widgets">
        <div class="inner-widgets-row">
            <div class="install-widget" v-if="!installed">
                <div class="inner-install-widget">
                  <div class="jumbotron">
                    <h1 class="display-4">Finish connecting to {{ shopType }}!</h1>
                    <p class="lead">{{ notInstalledCallToActionText }}</p>
                    <hr class="my-4">
                    <p>Click here to open a tab to out Shopify installer.</p>
                    <a class="btn btn-primary btn-lg" :href="'/?hmac=12345678923455426&shop='+shopId+'&timestamp='+getNowDate()" target="_blank" role="button">Link to Shopify</a>
                  </div>
                </div>
            </div>
        </div>
      </div>

      <!-- Middle-Column Row -->
      <div class="widget-row middle-column-row-shop-widgets">
        <div class="inner-widgets-row">
          <!-- Top Row -->
          <div class="left-column-shop-widgets">
            <div class="inner-widgets-column"></div>
          </div>

          <!-- Top Row -->
          <div class="right-column-shop-widgets">
            <div class="inner-widgets-column"></div>
          </div>
        </div>
      </div>

      <!-- Middle-Bottom Row -->
      <div class="widget-row middle-bottom-row-shop-widgets">
        <div class="inner-widgets-row"></div>
      </div>

      <!-- Bottom Row -->
      <div class="widget-row bottom-row-shop-widgets">
        <div class="inner-widgets-row"></div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
      name: "ShopDashboardComponent",
      props: ['loading', 'shopType', 'installed', 'shopId'],
      computed: {
          notInstalledCallToActionText() {
              let text = '';
              switch(this.shopType) {
                  case 'Shopify':
                    text = 'Connect your Shopify Shop to AllCommerce now to start making click funnels right away!'
                    break;

                  default:
                    text = 'Supported Type.'
              }

              return text;
          }
      },
      methods: {
          getNowDate() {
              return Date.now();
          }
      }
  }
</script>

<style scoped>

</style>
