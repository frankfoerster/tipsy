<template>
  <div class="view-support view-reset-password">
    <support-box :shake="shake">
      <template slot="title">Reset Password</template>
      <flash-message :type="flash.type" slot="content" v-if="flash.show">{{ flash.message }}</flash-message>
      <vue-form class="support-form" slot="content" @submit="validateBeforeSubmit">
        <form-row>
          <vue-label for="password">Password</vue-label>
          <vue-input
            v-model="password"
            :id="'password'"
            :type="'password'"
            :placeholder="'Password'"
            :icon="'lock'"
            :error="$v.password.$anyError"
            :autofocus="true"
            ref="pw"
          ></vue-input>
          <vue-input-error v-if="!$v.password.required">Please enter a password.</vue-input-error>
          <vue-input-error v-if="!$v.password.minLength">The password must contain at least 8 charaters.</vue-input-error>
        </form-row>
        <form-row>
          <vue-label for="password_confirmation">Password Confirmation</vue-label>
          <vue-input
            v-model="passwordConfirmation"
            :id="'password_confirmation'"
            :type="'password'"
            :placeholder="'Password Confirmation'"
            :icon="'lock'"
            :error="$v.passwordConfirmation.$anyError"
          ></vue-input>
          <vue-input-error v-if="!$v.passwordConfirmation.required">Please repeat your password.</vue-input-error>
          <vue-input-error v-if="!$v.passwordConfirmation.sameAsPassword">The passwords must be identical.</vue-input-error>
        </form-row>
        <vue-button :loading="loading">Reset Password</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Remember password? <router-link to="/login">Login</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { minLength, required, sameAs } from 'vuelidate/lib/validators';

  import flash from '../mixins/flashMixin';
  import handleErrors from '../mixins/handleErrorsMixin';

  import FormRow from '../components/FormRow.vue';
  import FlashMessage from '../components/FlashMessage.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueInputError from '../components/VueInputError.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'view-reset-password',

    mixins: [flash, handleErrors],

    data() {
      return {
        loading: false,
        shake: false,
        password: '',
        passwordConfirmation: ''
      }
    },

    validations: {
      password: {
        required,
        minLength: minLength(8)
      },
      passwordConfirmation: {
        required,
        sameAsPassword: sameAs('password')
      }
    },

    components: {
      FlashMessage,
      FormRow,
      SupportBox,
      VueButton,
      VueForm,
      VueInput,
      VueInputError,
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
        return this.$store.dispatch('RESET_PASSWORD', {
          token: this.$route.params.token,
          password: this.password,
          password_confirmation: this.passwordConfirmation
        }).then(() => {
          this.$router.push('/reset-password-complete');
        }).catch((data) => {
          this.showFlashMessage(data.message, 'error');
          this.$nextTick(() => {
            this.$refs.pw.$refs.input.focus();
          });
        });
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
