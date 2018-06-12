<template>
  <div class="top-ten">
    <ranking-row
      v-for="(user, index) in rankingSorted"
      :key="user.id"
      :user="user"
      :place="index + 1"
      :minimal="true"
      v-if="rankingSorted && index < 10"
      class="top-ten--row"
    ></ranking-row>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import RankingHeader from './RankingHeader.vue';
  import RankingRow from './RankingRow.vue';

  export default {
    name: 'top-ten',

    components: {
      RankingHeader,
      RankingRow
    },

    computed: {
      ...mapGetters([
        'rankingSorted'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
