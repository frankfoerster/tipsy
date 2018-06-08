<template>
  <div class="form-row">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    name: 'form-row'
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .form-row {
    @include rem(margin-bottom, 20px);

    &:last-child {
      margin-bottom: 0;
    }
  }
</style>
