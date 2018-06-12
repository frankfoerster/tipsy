<template>
  <div class="view-overview">
    <h1 class="page-title">Overview</h1>
    <div class="container">
      <div class="overview-container overview-container__groups">
        <card type="group" v-for="group in groups" :key="group.id" v-if="groups && groups.length > 0">
          <template slot="title">Group {{ group.name }}</template>
          <template slot="content">
            <group-teams :teams="teamsById(group.teams)"></group-teams>
            <group-footer :group="group"></group-footer>
          </template>
        </card>
      </div>
      <div class="overview-container overview-container__misc">
        <card type="playoffs">
          <template slot="title">Playoffs</template>
          <template slot="content">
            <playoffs></playoffs>
          </template>
        </card>
        <card type="top10">
          <template slot="title">
            <ranking-header :topTen="true"></ranking-header>
          </template>
          <template slot="content">
            <ranking-row
              v-for="(user, index) in rankingSorted"
              :key="user.id"
              :user="user"
              :place="index + 1"
              v-if="rankingSorted && index < 10"
            ></ranking-row>
          </template>
        </card>
      </div>
      <div class="overview-container overview-container__misc">
        <card type="upcoming">
          <template slot="title">Upcoming Games</template>
          <template slot="link"><router-link to="/games">All Games</router-link></template>
          <template slot="content">
            <upcoming-games></upcoming-games>
          </template>
        </card>
        <card type="vote-winner">
          <template slot="title">Who will win the {{ appTitle }}?</template>
          <template slot="content">
            <winner-vote></winner-vote>
          </template>
        </card>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import Card from '../components/Card.vue';
  import GroupFooter from '../components/GroupFooter.vue';
  import GroupTeams from '../components/GroupTeams.vue';
  import Playoffs from '../components/Playoffs.vue';
  import TopTen from '../components/TopTen.vue';
  import UpcomingGames from '../components/UpcomingGames.vue';
  import RankingHeader from '../components/RankingHeader.vue';
  import RankingRow from '../components/RankingRow.vue';
  import WinnerVote from '../components/WinnerVote.vue';

  export default {
    name: 'view-overview',

    components: {
      RankingRow,
      RankingHeader,
      Card,
      GroupFooter,
      GroupTeams,
      Playoffs,
      TopTen,
      UpcomingGames,
      WinnerVote
    },

    computed: {
      ...mapGetters([
        'appTitle',
        'groups',
        'teamsById',
        'rankingSorted'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .overview-container {
    display: flex;
    flex-wrap: wrap;
    @include rem(margin, 0 0 0 -10px);
  }

  .overview-container__groups {

    .card__group {
      width: 50%;

      @include respond-to-min(640px) {
        width: 25%;
      }

      @include respond-to-min(1280px) {
        width: 12.5%;
      }
    }
  }

  .overview-container__misc {

    .card {
      width: 100%;
      @include rem(padding-top, 20px);

      @include respond-to-min(640px) {
        width: 50%;
      }
    }
  }
</style>
