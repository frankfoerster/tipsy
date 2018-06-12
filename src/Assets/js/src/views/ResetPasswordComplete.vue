<template>
  <div class="view-support view-reset-password-complete">
    <support-box>
      <template slot="title">Your password <span>has been reset.</span></template>
      <template slot="footer">
        <p>You can <router-link to="/login">Login here</router-link>.</p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import SupportBox from '../components/SupportBox.vue';

  export default {
    name: 'view-reset-password-complete',

    components: {
      SupportBox
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-reset-password-complete {

    .support-box {
      @include rem(padding, 30px 20px);
    }

    .support-box--title {
      line-height: 1.4;
      text-align: center;

      span {
        white-space: nowrap;
      }

      @include respond-to-max(400px) {
        @include rem(font-size, 20px);
      }
    }

    .support-box--content {
      @include rem(margin, 30px 0 35px);
      line-height: 1.4;
      text-align: center;
    }
  }
</style>
