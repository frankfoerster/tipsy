<template>
  <div class="view-games">
    <h1 class="page-title">
      All Games
      <template v-if="secondaryTitle">
        <span class="page-title--divider">/</span>
        {{ secondaryTitle }}
      </template>
    </h1>
    <router-view></router-view>
  </div>
</template>

<script>
  import secondaryTitle from '../mixins/secondaryTitleMixin';

  import Games from '../components/Games.vue';

  export default {
    name: 'view-games',

    mixins: [secondaryTitle],

    components: {
      Games
    },

    computed: {
      secondaryTitle() {
        if (this.$route.name === 'games') {
          return false;
        }

        return this.getSecondaryTitle(this.$route.params.type)
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
