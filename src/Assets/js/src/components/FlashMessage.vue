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
  @import '../sass/imports';

  .flash-message {
    @include rem(padding, 20px);
    @include rem(margin-bottom, 20px);
    border-radius: 3px;
  }

  .flash-message__error {
    color: #fff;
    background-color: $rot3;
  }
</style>
