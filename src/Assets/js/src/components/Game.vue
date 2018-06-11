<template>
  <div class="game">
    <div class="game-header">
      <div class="game-header--date">{{ formatDate(game.playing_time, 'dd. DD.MM.YYYY, HH:mm') }}</div>
      <div class="game-header--day">1. Spieltag</div>
    </div>
    <div class="game-teams">
      <div class="game-team">
        <div class="game-team--name">
          {{ teamById(game.team1_id).name }}
        </div>
        <div class="game-team--image-wrapper">
          <img :src="baseUrl + teamById(game.team1_id).icon" :title="teamById(game.team1_id).name" :alt="teamById(game.team1_id).name" class="game-team--image">
        </div>
      </div>
      <div class="game-results">
        <div class="game-result">-</div>
        <div class="game-result--spacer">:</div>
        <div class="game-result">-</div>
      </div>
      <div class="game-team game-team__reverse">
        <div class="game-team--name">
          {{ teamById(game.team2_id).name }}
        </div>
        <div class="game-team--image-wrapper">
          <img :src="baseUrl + teamById(game.team2_id).icon" :title="teamById(game.team2_id).name" :alt="teamById(game.team2_id).name" class="game-team--image">
        </div>
      </div>
    </div>
    <game-vote :game="game" @voteFocus="onFocus" @voteBlur="onBlur"></game-vote>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import date from '../mixins/dateMixin';

  import GameVote from './GameVote.vue';

  export default {
    name: 'game',

    mixins: [date],

    props: {
      game: {
        type: Object,
        required: true
      }
    },

    components: {
      GameVote
    },

    data() {
      return {
        focused: false
      }
    },

    computed: {
      ...mapGetters([
        'baseUrl',
        'teamById'
      ])
    },

    methods: {
      onFocus() {
        this.$emit('gameFocus', this.game.id);
      },

      onBlur() {
        this.$emit('gameBlur', this.game.id);
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .game-header {
    display: flex;
    @include rem(font-size, 14px);
    font-weight: 600;
  }

  .game-header--date {
    width: 100%;
    flex-grow: 1;
  }

  .game-header--day {
    white-space: nowrap;
  }

  .game-teams {
    display: flex;
    @include rem(padding, 16px 0 10px);
    @include rem(font-size, 14px);
  }

  $resultsWidth: 40px;

  .game-team {
    display: flex;
    width: calc((100% - #{$resultsWidth})/2);
    align-items: center;
    flex-grow: 1;
    justify-content: space-between;
  }

  .game-team--name {
    order: 1;
    @include rem(padding-right, 5px);

    .game-team__reverse & {
      order: 2;
      text-align: right;
      padding-right: 0;
      @include rem(padding-left, 5px);
    }
  }

  .game-team--image-wrapper {
    order: 2;
    @include rem(padding-right, 8px);

    .game-team__reverse & {
      order: 1;
      padding-right: 0;
      @include rem(padding-left, 8px);
    }
  }

  .game-team--image {
    @include rem(width, 30px);
    height: auto;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.2);
  }

  .game-results {
    display: flex;
    @include rem(width, $resultsWidth);
    align-items: center;
    justify-content: center;
  }

  .game-result {
    width: calc((100% - 8px)/2);
    text-align: right;

    & ~ .game-result {
      text-align: left;
    }
  }

  .game-result--spacer {
    @include rem(width, 8px);
    @include rem(padding, 0 1px);
    text-align: center;
  }
</style>
