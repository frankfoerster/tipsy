<template>
  <div class="ranking-header">
    <div class="ranking-header--col ranking--place">
      <template v-if="topTen">T<span class="ranking-extension">op</span>10</template>
      <template v-else>P<span class="ranking-extension">lace</span></template>
    </div>
    <div class="ranking-header--col ranking--name">Name</div>
    <div class="ranking-header--col ranking--exact" title="Total number of votes that matched with the game result.">W<span class="ranking-extension">in</span></div>
    <div class="ranking-header--col ranking--tendency" title="Total number of votes that did not win but had the right tendency.">T<span class="ranking-extension">end.</span></div>
    <div class="ranking-header--col ranking--points" title="Total number of points.">P<span class="ranking-extension">oints</span></div>
  </div>
</template>

<script>
  export default {
    name: 'ranking-header',

    props: {
      topTen: {
        type: Boolean,
        required: false,
        default: false
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .ranking-header {
    display: flex;
    @include rem(margin, 0 -15px -1px);
    @include rem(padding, 0 15px);
    font-weight: 600;
    cursor: default;
  }
</style>
