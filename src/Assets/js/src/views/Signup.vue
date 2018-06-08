<template>
  <div class="view-support view-signup">
    <support-box :shake="shake">
      <template slot="title">Sign Up</template>
      <vue-form class="support-form" slot="content" :onSubmit="validateBeforeSubmit">
        <form-row>
          <vue-label for="email">Email</vue-label>
          <vue-input
            v-model="email"
            :id="'email'"
            :type="'text'"
            :placeholder="'Email'"
            :autofocus="true"
            :icon="'mail'"
            :error="$v.email.$anyError"
          ></vue-input>
          <vue-input-error v-if="!$v.email.required">Please enter your email address.</vue-input-error>
          <vue-input-error v-if="!$v.email.email">Please enter a valid email address.</vue-input-error>
        </form-row>
        <form-row>
          <vue-label for="user">Username</vue-label>
          <vue-input
            v-model="username"
            :id="'user'"
            :type="'text'"
            :placeholder="'Username'"
            :icon="'user'"
            :error="$v.username.$anyError"
          ></vue-input>
          <vue-input-error v-if="!$v.username.required">Please enter a username.</vue-input-error>
          <vue-input-error v-if="!$v.username.minLength">The username must contain at least 2 charaters.</vue-input-error>
        </form-row>
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
        <vue-button class="support-form--button">Signup</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Forgot <router-link to="/lost-password">Username / Password?</router-link></p>
        <p>Already a member? <router-link to="/login">Login</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { email, minLength, required, sameAs } from 'vuelidate/lib/validators';

  import FormRow from '../components/FormRow.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueInputError from '../components/VueInputError.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'signup',

    components: {
      FormRow,
      SupportBox,
      VueButton,
      VueForm,
      VueInput,
      VueInputError,
      VueLabel
    },

    data() {
      return {
        loading: false,
        shake: false,
        email: '',
        username: '',
        password: '',
        passwordConfirmation: ''
      }
    },

    validations: {
      email: {
        required,
        email: email
      },
      username: {
        required,
        minLength: minLength(2)
      },
      password: {
        required,
        minLength: minLength(8)
      },
      passwordConfirmation: {
        sameAsPassword: sameAs('password')
      }
    },

    methods: {
      validateBeforeSubmit() {
        this.$v.$touch();
        if (this.$v.$invalid) {
          this.shake = true;
        } else {
          console.log('signup');
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
