<template>
  <div class="view-ranking">
    <h1 class="page-title">All Rankings</h1>
    <card type="ranking">
      <template slot="title">
        <ranking-header></ranking-header>
      </template>
      <template slot="content">
        <ranking-row
          v-for="(user, index) in rankingSorted"
          :key="user.id"
          :user="user"
          :place="index + 1"
          v-if="rankingSorted && rankingSorted.length > 0"
        ></ranking-row>
      </template>
    </card>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import Card from '../components/Card.vue';
  import RankingHeader from '../components/RankingHeader.vue';
  import RankingRow from '../components/RankingRow.vue';

  export default {
    name: 'view-ranking',

    components: {
      Card,
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

  .view-ranking {

  }
</style>
