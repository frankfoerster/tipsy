<template>
  <div class="view-imprint">
    <h1 class="page-title">Imprint</h1>
    <transition name="fade">
      <div class="imprint-content" v-if="!loading">
        <div v-html="imprint" v-if="imprint"></div>
        <h2>Image Credit</h2>
        <ul>
          <li>Football icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
          <li>Flag icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
          <li>Trophy icon made by <a href="https://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
          <li>3rd place medal icon made by <a href="https://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
        </ul>
      </div>
    </transition>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'view-imprint',

    data() {
      return {
        loading: false
      }
    },

    computed: {
      ...mapGetters([
        'imprint'
      ])
    },

    mounted() {
      if (!this.imprint) {
        this.loading = true;
        this.$store.dispatch('FETCH_IMPRINT').finally(() => {
          this.loading = false;
        });
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .imprint-content {
    @include rem(padding, 20px);
    margin: 0 auto;
    @include rem(max-width, 640px);
  }
</style>
