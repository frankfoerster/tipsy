<template>
  <div class="view-support view-login">
    <support-box :shake="shake">
      <template slot="title">Login</template>
      <flash-message :type="flash.type" slot="content" v-if="flash.show">{{ flash.message }}</flash-message>
      <vue-form class="support-form" slot="content" @submit="validateBeforeSubmit">
        <form-row>
          <vue-label for="login">Login</vue-label>
          <vue-input
            v-model="login"
            id="login"
            type="text"
            placeholder="Email or Username"
            icon="user"
            :autofocus="true"
          ></vue-input>
        </form-row>
        <form-row>
          <vue-label for="password">Password</vue-label>
          <vue-input
            v-model="password"
            id="password"
            type="password"
            placeholder="Password"
            icon="lock"
            ref="password"
          ></vue-input>
        </form-row>
        <vue-button :loading="loading" :disabled="$v.$invalid">Login</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Lost your <router-link to="/lost-password">Password?</router-link></p>
        <p>Not a member? <router-link to="/signup">Sign up now</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { required } from 'vuelidate/lib/validators';

  import flash from '../mixins/flashMixin';
  import handleErrors from '../mixins/handleErrorsMixin';

  import FlashMessage from '../components/FlashMessage.vue';
  import FormRow from '../components/FormRow.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'view-login',

    mixins: [flash, handleErrors],

    data() {
      return {
        loading: false,
        shake: false,
        login: '',
        password: ''
      }
    },

    validations: {
      login: {
        required
      },
      password: {
        required
      }
    },

    components: {
      FlashMessage,
      FormRow,
      SupportBox,
      VueButton,
      VueForm,
      VueInput,
      VueLabel
    },

    methods: {
      validateBeforeSubmit() {
        this.$v.$touch();
        if (this.$v.$invalid) {
          this.shake = true;
        } else {
          this.loading = true;
          this.submit().finally(() => {
            this.loading = false;
          });
        }
      },

      submit() {
        const credentials = {
          login: this.login,
          password: this.password
        };

        this.password = '';

        return this.$store.dispatch('LOGIN',
          credentials
        ).then((data) => {
          const redirect = this.$store.getters.redirect;
          this.$store.dispatch('FORGET_LOGIN_REDIRECT');
          this.$router.push(redirect || '/');
        }).catch((data) => {
          if (data.status && data.status === 500) {
            this.showFlashMessage('Authentication failed.', 'error');
          } else {
            this.showFlashMessage(data.message, 'error');
          }
          this.$nextTick(() => {
            this.$refs.password.focus();
          });
        });
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-support {
    @include rem(padding, 10px 10px 0);
    min-height: calc(100vh - 103px);
    display: flex;
    align-items: center;
    justify-content: center;

    @include respond-to-min(400px) {
      @include rem(padding, 20px 20px 0);
    }
  }

  .support-form {
    @include rem(margin-bottom, 20px);

    @include respond-to-min(400px) {
      @include rem(margin-bottom, 40px);
    }
  }
</style>
