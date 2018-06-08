<template>
  <label>
    <slot></slot>
  </label>
</template>

<script>
  export default {
    name: 'vue-label'
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  label {
    @include visuallyhidden;
  }
</style>
