<template>
  <div class="view-support view-login">
    <support-box :shake="shake">
      <template slot="title">Login</template>
      <template slot="content">
        <flash-message type="'error'" v-if="$v.$anyError">Invalid Credentials.</flash-message>
      </template>
      <vue-form class="support-form" slot="content" :onSubmit="validateBeforeSubmit">
        <form-row>
          <vue-label for="login">Login</vue-label>
          <vue-input
            :id="'login'"
            :type="'text'"
            :placeholder="'Email or Username'"
            :autofocus="true"
            :icon="'user'"
            :error="$v.$anyError"
          ></vue-input>
        </form-row>
        <form-row>
          <vue-label for="password">Password</vue-label>
          <vue-input
            :id="'password'"
            :type="'password'"
            :placeholder="'Password'"
            :icon="'lock'"
            :error="$v.$anyError"
          ></vue-input>
        </form-row>
        <vue-button class="support-form--button">Login</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Forgot <router-link to="/lost-password">Username / Password?</router-link></p>
        <p>Not a member? <router-link to="/signup">Sign up now</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { required } from 'vuelidate/lib/validators';

  import FlashMessage from '../components/FlashMessage.vue';
  import FormRow from '../components/FormRow.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'login',

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
          console.log('login');
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-support {
    @include rem(padding, 20px);
    min-height: calc(100vh - 82px);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .support-form {
    @include rem(margin-bottom, 40px)
  }

  .support-form--button {
    color: #fff;
    text-transform: uppercase;
    width: 100%;
    height: 62px;
    border-radius: 3px;
    background-color: $rot;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 25px;
    transition: background-color 0.2s linear;
    will-change: background-color;

    &:hover, &:focus {
      background-color: $rot2;
    }
  }
</style>
