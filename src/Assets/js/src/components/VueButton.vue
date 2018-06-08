<template>
  <button>
    <slot></slot>
  </button>
</template>

<script>
  export default {
    name: 'vue-button'
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  button {
    cursor: pointer;
    outline: none;
    border: none;
  }
</style>
