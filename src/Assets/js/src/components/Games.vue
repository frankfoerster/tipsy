<template>
  <div class="games">
    <card v-for="(game, index) in getGames" type="game" :class="cardClasses(game)" v-if="getGames">
      <template slot="content">
        <game :game="game" @gameFocus="onFocus" @gameBlur="onBlur"></game>
      </template>
    </card>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import canVote from '../mixins/canVoteMixin';
  import focusable from '../mixins/focusableMixin';
  import stringIsNumeric from '../mixins/stringIsNumericMixin';

  import Card from '../components/Card.vue';
  import Game from '../components/Game.vue';

  export default {
    name: 'games',

    mixins: [canVote, focusable, stringIsNumeric],

    components: {
      Card,
      Game
    },

    computed: {
      ...mapGetters([
        'gamesByGroup',
        'gamesByType'
      ]),

      getGames() {
        const type = this.$route.params.type;

        if (this.stringIsNumeric(type)) {
          return this.gamesByGroup(type);
        }

        return this.gamesByType(type);
      }
    },

    methods: {
      cardClasses(game) {
        return [
          { 'card__game-focused': this.isFocused[game.id] },
          { 'card__game-voting-closed': !this.canVote(game) }
        ]
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
