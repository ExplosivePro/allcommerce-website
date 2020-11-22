<template>
  <div class="membership-carousel" :data-slick="slickConfig">
      <div class="no-memberships" v-if="content.length === 0">
        <div class="card bg-danger text-center">
            <div class="card-body">
                <h2> Sorry, chum. </h2>
                <p>No memberships available, right now.</p>
            </div>
        </div>
      </div>
    <div class="" v-for="(widget ,idx) in content" v-else>
      <div class="card">
        <img class="bd-placeholder-img card-img-top" :src="widget['misc']['img']" height="180"/>

        <div class="card-body bg-orange" style="height: 10em">
          <h5 class="card-title">{{ widget['name'] }}</h5>
          <p class="card-text"><i>{{ widget['desc'] }}</i></p>
        </div>

        <ul class="list-group list-group-flush">
          <li class="list-group-item">{{ widget['recurring'] === 1 ? 'Bills every '+widget['frequency']+' days.' : widget['frequency']+' day trial' }}</li>
          <li class="list-group-item">Price ${{ widget['price'] }} {{ widget['trial'] === 0 ? '/mo' : 'one-time'}}</li>
        </ul>
        <div class="card-body text-center">
          <button type="button" class="btn btn-dark text-light" @click="membershipSelect(widget)"
                  :title="'Buy '+widget['name']+' for $'+widget['price']"><i class="fad fa-ticket-alt"></i> Subscribe Now!</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import 'slick-carousel';
  export default {
    name: "MembershipCarouselCheckout",
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
      membershipSelect(membership) {
        this.$emit('membership', membership)
      }
    },
    mounted() {
      $('.membership-carousel').slick();
      console.log('content', this.content);
    }
  }
</script>

<style scoped>
    @media screen {
        .no-memberships {
          margin-top: 5em !important;
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
