<template>
  <div class="container">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    name: 'container'
  };
</script>

<style lang="scss">
  @import '../sass/imports';

  .container {
    max-width: 1300px;
    margin: 0 auto;
    @include rem(padding, 0 10px);
  }
</style>
