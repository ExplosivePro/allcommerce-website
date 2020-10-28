<template>
    <shop-dash
        :loading="loading"
        :shop-type="shopType"
        :installed="installed"
        :shop-id="shopId"
    ></shop-dash>
</template>

<script>
  import ShopDash from "../../presenters/dashboards/ShopDashboardComponent";

  import { mapState, mapMutations, mapActions } from 'vuex';
  export default {
      name: "ShopDashboardContainer",
      components: {
          ShopDash
      },
      props: ['client', 'merchant', 'shop'],
      watch: {
        installed(flag) {
            console.log('Shopify Install Status Updated - ' + flag);
            this.loading = false;
        }
      },
      data() {
          return {
              loading: true,
              shopType: '',
              shopId: ''
          };
      },
      computed: {
          ...mapState('shopDash', ['loadedShop']),
          ...mapState('shopDash/shopify', ['installed']),
      },
      methods: {
          getShopType() {
              if('shop_type' in this.shop) {
                this.shopType = this.shop['shop_type'].name;
                this.shopId = this.shop['shop_url'];
                this.setShop(this.shop);

                switch(this.shop['shop_type'].name) {
                    case 'Shopify':
                      this.getShopifyInstallStatus();
                    break;
                }
              }
          },
          ...mapMutations({
            setShop: 'shopDash/setActiveShop'
          }),
          ...mapActions({
              getShopifyInstallStatus: 'shopDash/getShopifyInstallStatus',
          })
      },
      mounted() {
          this.getShopType();
          console.log('ShopDashboardContainer mounted!', this.shop['shop_type'].name);
      }
  }
</script>

<style scoped>
    @media screen {
        .dashboard {
            width: 100%;
            height: 100%;
        }

      .inner-dashboard {
          display: flex;
          flex-flow: column;
      }

      .widget-row {
          width: 100%;
      }
    }

    @media screen and (max-width: 999px) {
       .middle-column-row-shop-widgets .inner-widgets-row {
         display: flex;
         flex-flow: column;
       }

        .left-column-shop-widgets, .right-column-shop-widgets {
            width: 100%;
            height: 100%;
        }
    }

    @media screen and (min-width: 1000px) {
        .middle-column-row-shop-widgets .inner-widgets-row {
            display: flex;
            flex-flow: row;
        }

        .left-column-shop-widgets, .right-column-shop-widgets {
            width: 50%;
            height: 100%;
        }
    }
</style>
