<template>
  <div class="input-error-message">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    name: 'vue-input-error'
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .input-error-message {
    display: none;
    @include rem(padding-top, 10px);
    color: $rot;

    .input-error + & {
      display: block;
    }
  }
</style>
