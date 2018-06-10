<template>
  <form novalidate="novalidate" @submit.prevent="onSubmit($event)">
    <slot></slot>
  </form>
</template>

<script>
  export default {
    name: 'vue-form',

    methods: {
      onSubmit(event) {
        this.$emit('submit', event);
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
