<template>
  <div class="card-body row slicky"  :data-slick="slickConfig">
    <div v-for="(drawing, idx) in widget['content']">
      <div class="card" style="width: 18rem;">
        <img class="bd-placeholder-img card-img-top" :src="drawing['main-image']" height="125"/>

        <div class="card-body text-dark">
          <h5 class="card-title">{{ drawing.name }}</h5>
          <p class="card-text">{{ drawing.subtitle }}</p>
          <p class="card-text" style="margin:0;">Ends - {{ drawing['listing_end'] }}</p>
        </div>
        <div class="card-body">
          <button type="button" class="btn btn-warning"><i class="fad fa-ticket"></i> {{ drawing['credit_cost']}} Credits</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "RaffleListings",
  props: ['widget'],
  data() {
    return {
      slickConfig: JSON.stringify({
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
  mounted() {
    $('.slicky').not('.slick-initialized').slick()
  }
}
</script>

<style scoped>
    @media screen {
        .slicky {

        }

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
