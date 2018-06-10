<template>
  <div class="view-support view-reset-password">
    <support-box :shake="shake">
      <template slot="title">Reset Password</template>
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
          <vue-input-error v-if="!$v.passwordConfirmation.required">The passwords must be identical.</vue-input-error>
        </form-row>
        <vue-button>Reset Password</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Remember password? <router-link to="/login">Login</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { minLength, required, sameAs } from 'vuelidate/lib/validators';

  import FormRow from '../components/FormRow.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueInputError from '../components/VueInputError.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'reset-password',

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
        sameAsPassword: sameAs('password')
      }
    },

    components: {
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
          console.log('reset password');
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
