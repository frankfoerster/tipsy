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
  .container {
    max-width: 1300px;
    margin: 0 auto;
  }
</style>
