<template>
  <div class="view-support view-lost-password-requested">
    <support-box>
      <template slot="title">Please check <span>your inbox</span></template>
      <p class="support-box--content" slot="content">We have sent you an email to reset your password.</p>
      <template slot="footer">
        <p>You can <router-link to="/login">Login here</router-link>.</p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import SupportBox from '../components/SupportBox.vue';

  export default {
    name: 'view-lost-password-requested',

    components: {
      SupportBox
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-lost-password-requested {

    .support-box {
      @include rem(padding, 30px 20px);
    }

    .support-box--title {
      line-height: 1.4;
      text-align: center;

      span {
        white-space: nowrap;
      }
    }

    .support-box--content {
      @include rem(margin, 30px 0 35px);
      line-height: 1.4;
      text-align: center;
    }
  }
</style>
