<template>
  <div class="ranking-row" :class="rowClasses">
    <div class="ranking-row-col ranking--place">{{ place }}.</div>
    <div class="ranking-row-col ranking--name"><span class="ranking-row--username">{{ user.username }}</span><img :src="trophyIcon" class="ranking--winner-team-icon" v-if="user.total_bonus_points > 0" :title="user.username + ' voted for the winning team. (+10 points)'"></div>
    <div class="ranking-row-col ranking--exact">{{ user.total_exact }}</div>
    <div class="ranking-row-col ranking--tendency">{{ user.total_tendency }}</div>
    <div class="ranking-row-col ranking--points"><span class="ranking--points--help" :title="user.total_points + ' points with ' + user.total_votes + ' total votes'">{{ user.total_points }}</span></div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'ranking-row',

    props: {
      place: {
        type: Number,
        required: true
      },
      user: {
        type: Object,
        required: true
      }
    },

    data() {
      return {
        trophyIcon: global.window.AppConfig.trophyIcon
      }
    },

    computed: {
      ...mapGetters({
        currentUser: 'user'
      }),

      rowClasses() {
        return {
          'ranking-row__top3': this.place <= 3,
          'ranking-row__4to10': this.place >= 4 && this.place <= 10,
          'ranking-row__three': this.place === 3,
          'ranking-row__ten': this.place === 10,
          'ranking-row__user': this.currentUser && this.user.id === this.currentUser.id
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .ranking-row {
    display: flex;
    @include rem(margin, 0 -15px -1px);
    @include rem(padding, 9px 15px 6px);
    border-bottom: 1px solid #f5f5f5;
    cursor: default;
    transition: background-color 0.2s linear;

    &:last-of-type {
      border-bottom: none;
    }

    &:hover {
      background-color: #f5f5f5;
    }
  }

  .ranking-row__three,
  .ranking-row__ten {

    &:not(:last-of-type) {
      margin-bottom: 0;
      border-bottom: 1px solid #d4d4d4;
    }
  }

  .ranking-row__user {
    &, &:hover {
      background-color: #fff6bf;
    }
  }

  .ranking--place {
    @include rem(width, 24px);
    font-weight: 600;
    text-align: right;

    .ranking-row__top3 & {
      color: #51832a;
    }

    .ranking-row__4to10 & {
      color: #bf580c;
    }
  }

  .ranking--name {
    width: calc(100% - 124px);
    @include rem(padding-left, 10px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .ranking--exact {
    color: #51832a;
    @include rem(width, 30px);
    text-align: right;
  }

  .ranking--tendency {
    color: #bf580c;
    @include rem(width, 30px);
    text-align: right;
  }

  .ranking--points {
    @include rem(width, 40px);
    font-weight: 600;
    text-align: right;
  }

  .ranking-extension {
    display: none;
  }

  @mixin ranking-extended {

    .ranking-extension {
      display: inline;
    }

    .ranking--place {
      @include rem(width, 40px);
    }

    .ranking--name {
      width: calc(100% - 200px);
      @include rem(padding-left, 20px);
    }

    .ranking--exact {
      @include rem(width, 40px);
      @include rem(padding-right, 10px);
    }

    .ranking--tendency {
      @include rem(width, 50px);
    }

    .ranking--points {
      @include rem(width, 70px);
    }
  }

  .route-overview {

    @include respond-to-min-max(560px, 639px) {
      @include ranking-extended;
    }

    @include respond-to-min(1024px) {
      @include ranking-extended;
    }

    @include respond-to-min(1024px) {
      @include ranking-extended;
    }
  }

  .route-ranking {

    @include respond-to-min(560px) {
      @include ranking-extended;
    }
  }

  .ranking--winner-team-icon {
    @include rem(max-width, 10px);
    @include rem(margin-left, 5px);
    cursor: help;
  }

  .ranking--points--help {
    cursor: help;
  }
</style>
