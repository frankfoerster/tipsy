<template>
  <div class="playoffs">
    <router-link class="playoff" to="/games/last-sixteen">
      <div class="playoff--icon">
        <div class="playoff--icon--text">&frac18;</div>
      </div>
      <div class="playoff--content">
        <div class="playoff--title">Last Sixteen</div>
        <div class="playoff--period">{{ periodLastSixteen }}</div>
      </div>
    </router-link>
    <router-link class="playoff" to="/games/quarter-final">
      <div class="playoff--icon">
        <div class="playoff--icon--text">&frac14;</div>
      </div>
      <div class="playoff--content">
        <div class="playoff--title">Quarter Final</div>
        <div class="playoff--period">{{ periodQuarterFinal }}</div>
      </div>
    </router-link>
    <router-link class="playoff" to="/games/semi-final">
      <div class="playoff--icon">
        <div class="playoff--icon--text">&frac12;</div>
      </div>
      <div class="playoff--content">
        <div class="playoff--title">Semi Final</div>
        <div class="playoff--period">{{ periodSemiFinal }}</div>
      </div>
    </router-link>
    <router-link class="playoff" to="/games/3rd-place">
      <div class="playoff--icon"><img :src="medalIcon3rd"></div>
      <div class="playoff--content">
        <div class="playoff--title">Game for 3rd Place</div>
        <div class="playoff--period">{{ period3rdPlace }}</div>
      </div>
    </router-link>
    <router-link class="playoff" to="/games/final">
      <div class="playoff--icon"><img :src="trophyIcon"></div>
      <div class="playoff--content">
        <div class="playoff--title">Final</div>
        <div class="playoff--period">{{ periodFinal }}</div>
      </div>
    </router-link>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'playoffs',

    data() {
      return {
        medalIcon3rd: global.window.AppConfig.medalIcon3rd,
        trophyIcon: global.window.AppConfig.trophyIcon
      }
    },

    computed: {
      ...mapGetters([
        'periodLastSixteen',
        'periodQuarterFinal',
        'periodSemiFinal',
        'period3rdPlace',
        'periodFinal',
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .playoff {
    display: flex;
    @include rem(margin, 0 -15px -1px);
    @include rem(padding-left, 15px);
    color: #000;
    transition: background-color 0.2s linear;

    &:hover, &:focus {
      color: #000;
      background-color: #f5f5f5;
      text-decoration: none;
    }
  }

  .playoff--icon {
    display: flex;
    @include rem(width, 60px);
    @include rem(padding, 0 15px 0 0);
    align-items: center;
    justify-content: center;

    img {
      max-width: 100%;
      width: 100%;
      height: auto;
      max-height: 50px;
    }
  }

  .playoff--icon--text {
    display: flex;
    @include rem(width, 40px);
    @include rem(height, 40px);
    align-items: center;
    justify-content: center;
    @include rem(font-size, 21px);
    color: #fff;
    background-color: $rot;
    border-radius: 50%;
  }

  .playoff--content {
    position: relative;
    @include rem(padding, 16px 60px 14px 0);
    flex-grow: 1;
    border-bottom: 1px solid #f5f5f5;

    .playoff:last-of-type & {
      border-bottom: none;
    }

    &:after {
      position: absolute;
      top: 50%;
      right: 10px;
      color: #757575;
      @include icon-direct($icon-angle-down);
      @include rem(font-size, 28px);
      transform: rotate(-90deg) translateX(50%);
    }
  }

  .playoff--title {
    @include rem(padding, 0 0 3px);
  }

  .playoff--period {
    @include rem(font-size, 14px);
    color: #757575;
  }
</style>
