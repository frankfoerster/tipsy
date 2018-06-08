<template>
  <div class="flash-message" :class="classes(type)">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    name: 'flash-message',

    props: {
      type: {
        type: String,
        required: true
      },
    },

    methods: {
      classes(type) {
        return [
          `flash-message__${type}`
        ];
      }
    }
  }
</script>

<style lang="scss">

</style>
