<template>
  <div class="upcoming-games">
    <div v-for="(game, index) in upcomingGames" v-if="upcomingGames && upcomingGames.length > 0 && index < 5"
         class="upcoming--row"
         :class="{'upcoming--row__focused': focused[game.id]}"
    >
      <game :game="game" @gameFocus="onFocus" @gameBlur="onBlur"></game>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import Vue from 'vue';

  import Game from './Game.vue';

  export default {
    name: 'upcoming-games',

    components: {
      Game
    },

    data() {
      return {
        focused: {}
      }
    },

    computed: {
      ...mapGetters([
        'upcomingGames'
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

  .upcoming--row {
    @include rem(margin, 0 -15px -1px);
    @include rem(padding, 16px 15px 7px);
    border-bottom: 1px solid #f5f5f5;
    cursor: default;
    transition: background-color 0.2s linear;

    &:last-of-type {
      border-bottom: 0;
    }

    &:hover {
      background-color: #f5f5f5;
    }
  }

  .upcoming--row__focused {

    &, &:hover {
      background-color: #fff6bf;
    }
  }
</style>
