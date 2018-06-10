<template>
  <div class="group-footer">
    <div class="group-footer--item">
      <router-link :to="'/games/group/' + group.id">Games</router-link>
    </div>
    <div class="group-footer--item">
      <router-link :to="'/table/group/' + group.id">Table</router-link>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'group-footer',

    props: {
      group: {
        type: Object,
        required: true
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .group-footer {
    display: flex;
  }

  .group-footer--item {
    width: 50%;
    @include rem(font-size, 14px);
    font-weight: 600;
    text-align: left;

    &:last-of-type {
      text-align: right;
    }

    @include respond-to-min-max(480px, 639px) {

      &, &:last-of-type {
        text-align: center;
      }
    }

    @include respond-to-min-max(900px, 1279px) {

      &, &:last-of-type {
        text-align: center;
      }
    }
  }
</style>
