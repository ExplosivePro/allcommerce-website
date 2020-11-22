<template>
  <div class="md-form active-cyan-2 mb-3">
    <input class="form-control" type="text" placeholder="Search For Raffle Drawings..."
           aria-label="Search" v-on:keyup.enter="initSearch()">
  </div>
</template>

<script>
export default {
    name: "DashboardSearchBar",
    methods: {
        initSearch() {
            alert('Search, coming soon!');
        }
    }
}
</script>

<style scoped>

</style>
