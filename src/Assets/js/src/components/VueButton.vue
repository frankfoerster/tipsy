<template>
  <button :class="buttonClasses" :disabled="loading || disabled" @click="onClick($event)">
    <transition name="fade-spinner" mode="out-in" :duration="150">
      <div :class="spinnerClasses" v-if="loading"></div>
    </transition>
    <slot></slot>
  </button>
</template>

<script>
  export default {
    name: 'vue-button',

    props: {
      loading: {
        type: Boolean,
        required: false,
        default: false
      },
      disabled: {
        type: Boolean,
        required: false,
        default: false
      }
    },

    computed: {
      buttonClasses() {
        return {
          'button': true,
          'button__loading': this.loading
        };
      },

      spinnerClasses() {
        return [
          'button--spinner'
        ]
      },
    },

    methods: {
      onClick(event) {
        this.$emit('click', event);
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .fade-spinner-enter-active,
  .fade-spinner-leave-active {
    transition: opacity 1s;
  }

  .fade-spinner-enter,
  .fade-spinner-leave-active {
    opacity: 0;
    will-change: opacity;
  }

  .button {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    background-color: $rot;
    text-transform: uppercase;
    border-radius: 3px;
    cursor: pointer;
    outline: none;
    border: none;
    user-select: none;
    transition: background-color 0.2s linear;
    will-change: background-color;

    &:hover, &:focus {
      background-color: $rot2;
    }

    &[disabled="disabled"] {
      background-color: $rot4;
      cursor: default;
      pointer-events: none;
    }

    .support-form & {
      width: 100%;
      @include rem(padding, 22px 25px);
    }
  }

  .button--spinner {
    @include rem(width, 16px);
    @include rem(height, 16px);
    @include rem(margin-left, -22px);
    @include rem(margin-right, 6px);
    opacity: 1;
    animation: loading-button-spinner-rotation .7s infinite linear;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: #efefef;
    border-radius: 100%;
    transition: .3s all ease;

    .support-form & {
      @include rem(width, 30px);
      @include rem(height, 30px);
      @include rem(margin, -6px 10px -6px -40px);
    }
  }

  @keyframes loading-button-spinner-rotation {
    from {
      transform: rotate(0deg)
    }
    to {
      transform: rotate(359deg)
    }
  }
</style>
