<template>
  <div class="view-games">
    <card v-for="(game, index) in games" :key="game.id" type="game" :class="{'card__game-focused': focused[game.id], 'card__game-voting-closed': !canVote(game)}">
      <template slot="content">
        <game :game="game" @gameFocus="onFocus" @gameBlur="onBlur"></game>
      </template>
    </card>
  </div>
</template>

<script>
  import Vue from 'vue';
  import { mapGetters } from 'vuex';

  import canVote from '../mixins/canVoteMixin';

  import Card from '../components/Card.vue';
  import Game from '../components/Game.vue';

  export default {
    name: 'games',

    mixins: [canVote],

    components: {
      Card,
      Game
    },

    data() {
      return {
        focused: {}
      }
    },

    computed: {
      ...mapGetters([
        'games'
      ])
    },

    methods: {
      onFocus(gameId) {
        Vue.set(this.focused, gameId, true);
      },

      onBlur(gameId) {
        Vue.set(this.focused, gameId, false);
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-games {
    @include rem(padding-top, 10px);
  }
</style>
