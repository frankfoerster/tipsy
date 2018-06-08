<template>
  <form novalidate="novalidate" @submit.prevent="onSubmit">
    <slot></slot>
  </form>
</template>

<script>
  export default {
    name: 'vue-form',

    props: {
      onSubmit: {
        type: Function,
        required: true
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  form {
    margin: 0;
    padding: 0;
  }
</style>
