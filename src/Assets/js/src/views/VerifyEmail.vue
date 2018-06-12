<template>
  <div class="view-support view-verify-email">
    <support-box>
      <template slot="title" v-if="loading && isVerifyRoute">Verifying Email ...</template>
      <template slot="title" v-if="loading && isRequestVerificationRoute">Resending Verification ...</template>
      <template slot="title" v-if="!loading && verified">Email Verified</template>
      <template slot="title" v-if="!loading && error">Something went wrong</template>
      <template slot="title" v-if="!loading && mailSent">Email sent</template>
      <transition slot="content">
        <flash-message :type="flash.type" v-if="flash.show">{{ flash.message }}</flash-message>
      </transition>
      <template slot="content">
        <transition name="loader-fade" appear :duration="1000">
          <div v-if="loading" class="verification-container">
            <loading-svg class="status-svg"></loading-svg>
          </div>
          <div v-if="!loading && (verified || mailSent)" class="verification-container">
            <checkmark-svg class="status-svg"></checkmark-svg>
          </div>
          <div v-if="!loading && error" class="verification-container">
            <bummer-svg class="status-svg"></bummer-svg>
          </div>
        </transition>
      </template>
      <template slot="footer">
        <p v-if="!mailSent && !verified">Lost your <router-link to="/lost-password">Password?</router-link></p>
        <p v-if="!mailSent && !verified">Not a member? <router-link to="/signup">Sign up now</router-link></p>
        <p v-if="!mailSent && verified">You can <router-link to="/login">Login here</router-link>.</p>
        <p v-if="mailSent">Back to <router-link to="/">Game Plan</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import flash from '../mixins/flashMixin';

  import BummerSvg from '../components/BummerSvg.vue';
  import CheckmarkSvg from '../components/CheckmarkSvg.vue';
  import FlashMessage from '../components/FlashMessage.vue';
  import LoadingSvg from '../components/LoadingSvg.vue';
  import SupportBox from '../components/SupportBox.vue';

  export default {
    name: 'view-verify-email',

    mixins: [flash],

    data() {
      return {
        error: false,
        loading: true,
        verified: false,
        mailSent: false
      }
    },

    components: {
      BummerSvg,
      CheckmarkSvg,
      FlashMessage,
      LoadingSvg,
      SupportBox,
    },

    computed: {
      isVerifyRoute() {
        return this.$route.name === 'verify-email';
      },
      isRequestVerificationRoute() {
        return this.$route.name === 'request-verification';
      }
    },

    mounted() {
      setTimeout(() => {
        const routeToken = this.$route.params.token;

        if (routeToken) {
          this.$store.dispatch('VERIFY_EMAIL', routeToken)
            .then((data) => {
              this.verified = true;
              this.showFlashMessage(data.message, 'success');
            })
            .catch((data) => {
              this.error = true;
              this.showFlashMessage(data.message, 'error');
            })
            .finally(() => {
              this.loading = false;
            })
        } else {
          const token = this.$store.getters.token;
          this.$store.dispatch('REQUEST_VERIFICATION_EMAIL', token)
            .then((data) => {
              this.mailSent = true;
              this.showFlashMessage(data.message, 'success');
            })
            .catch((data) => {
              this.error = true;
              this.showFlashMessage(data.message, 'error');
            })
            .finally(() => {
              this.loading = false;
            });
        }
      }, 2000);
    }
  };
</script>

<style lang="scss" scoped>
  @import '../sass/imports';

  .loader-fade-enter-active, .loader-fade-leave-active {
    transition: opacity .5s;
  }
  .loader-fade-enter, .loader-fade-leave-to {
    opacity: 0;
  }

  .verification-container {
    display: flex;
    justify-content: center;
    @include rem(margin-bottom, 40px);
  }

  .status-svg {
    width: 200px;
    height: 200px;
  }
</style>

<style lang="scss">
  @import '../sass/imports';

  .view-verify-email {

    .support-box--title {
      text-align: center;
    }

    .flash-message {
      @include rem(margin-top, -20px);
      text-align: center;
      color: #000;
      background-color: transparent;
    }
  }
</style>
