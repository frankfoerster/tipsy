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
    @include rem(padding, 10px 15px);
    @include rem(margin-bottom, 20px);
    line-height: 1.4;
    border-radius: 3px;

    @include respond-to-min(400px) {
      @include rem(padding, 20px);
    }
  }

  .flash-message__error {
    color: #fff;
    background-color: $rot3;
  }
</style>
