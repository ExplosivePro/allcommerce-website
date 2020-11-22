<template>
  <div class="credit-carousel" :data-slick="slickConfig">
    <div class="" v-for="(widget ,idx) in content">
      <div class="card">
        <img class="bd-placeholder-img card-img-top" :src="widget['attributes']['misc']['img']" height="180"/>

        <div :class="widget['class']" style="height: 10em">
          <h5 class="card-title">{{ widget['label'] }}</h5>
          <p class="card-text"><i>{{ widget['attributes']['desc'] }}</i></p>
        </div>

        <ul class="list-group list-group-flush">
          <li class="list-group-item">Credits: {{ widget['attributes']['qty'] }}</li>
          <li class="list-group-item">Price ${{ widget['attributes']['price'] }}</li>
        </ul>
        <div class="card-body text-center">
          <button type="button" class="btn btn-warning faa-spin" @click="bundleSelect(widget)"
                  :title="'Buy '+widget['label']+' for $'+widget['price']"><i class="fad fa-ticket-alt"></i> Buy Now!</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import 'slick-carousel';

export default {
  name: "CreditCarouselCheckout",
  props: ['content'],
  watch: {},
  data() {
    return {
        slickConfig: JSON.stringify({
          dots: true,
          speed: 750,
          slidesToShow: 3,
          slidesToScroll: 3,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 680,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 505,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        })
    };
  },
  computed: {},
  methods: {
      bundleSelect(bundle) {
          this.$emit('bundle', bundle)
      }
  },
  mounted() {
    $('.credit-carousel').slick();
    console.log('content-length', this.content.length);
  }
}
</script>

<style scoped>
    @media screen {
      .card {
          margin-left: 10% !important;
          margin-right: 10% !important;
      }

      .bd-placeholder-img.card-img-top {
        object-fit: contain;
        width: 100%;
      }
    }
</style>
