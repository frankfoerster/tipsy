<template>
  <div class="support-box">
    <h1 class="support-box--title">
      <slot name="title"></slot>
    </h1>
    <slot name="content"></slot>
    <div class="support-box--footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'support-box',

    props: {
      shake: {
        type: Boolean,
        required: false,
        default: false
      }
    },

    watch: {
      shake(val) {
        if (val === true) {
          this.$el.classList.add('animate-shake');
          setTimeout(() => {
            this.$el.classList.remove('animate-shake');
            if (typeof this.$parent.shake !== 'undefined') {
              this.$parent.shake = false;
            }
          }, 1000)
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .support-box {
    @include rem(padding, 40px);
    width: 100%;
    @include rem(max-width, 440px);
    background-color: #fff;
    box-shadow: 0 0 40px rgba(0,0,0,0.6);
    border-radius: 3px;
  }

  .support-box--title {
    @include rem(margin, 0 0 30px);
  }

  .support-box--footer {
    color: #999999;
    text-align: center;

    p {
      margin: 0 0 1em;

      &:last-child {
        margin-bottom: 0;
      }
    }
  }

  .animate-shake {
    animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) forwards 1;
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
    perspective: 1000px;
  }

  @keyframes shake {
    10%, 90% {
      transform: translate3d(-1px, 0, 0);
    }

    20%, 80% {
      transform: translate3d(2px, 0, 0);
    }

    30%, 50%, 70% {
      transform: translate3d(-4px, 0, 0);
    }

    40%, 60% {
      transform: translate3d(4px, 0, 0);
    }
  }
</style>
