<template>
  <div class="view-support view-signed-up">
    <support-box>
      <template slot="title">We've signed <span>you up</span></template>
      <template slot="footer">
        <p>You can <router-link to="/login">Login here</router-link>.</p>
        <p>Lost your <router-link to="/lost-password">Password?</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import SupportBox from '../components/SupportBox.vue';

  export default {
    name: 'view-signed-up',

    components: {
      SupportBox
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-signed-up {

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
  }
</style>
