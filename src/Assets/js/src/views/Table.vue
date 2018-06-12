<template>
  <div class="view-table">
    <h1 class="page-title">
      All Results
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

  export default {
    name: 'view-table',

    mixins: [secondaryTitle],

    computed: {
      secondaryTitle() {
        if (this.$route.name === 'table') {
          return false;
        }

        return this.getSecondaryTitle(this.$route.params.groupId);
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
