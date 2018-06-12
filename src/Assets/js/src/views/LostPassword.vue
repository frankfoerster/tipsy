<template>
  <div class="view-support view-lost-password">
    <support-box :shake="shake">
      <template slot="title">Lost Password</template>
      <vue-form class="support-form" slot="content" @submit="validateBeforeSubmit">
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
        <vue-button :loading="loading">Submit</vue-button>
      </vue-form>
      <template slot="footer">
        <p>Remember Password? <router-link to="/login">Login</router-link></p>
        <p>Not a member? <router-link to="/signup">Sign up now</router-link></p>
      </template>
    </support-box>
  </div>
</template>

<script>
  import { email, required } from 'vuelidate/lib/validators';

  import FormRow from '../components/FormRow.vue';
  import SupportBox from '../components/SupportBox.vue';
  import VueButton from '../components/VueButton.vue';
  import VueForm from '../components/VueForm.vue';
  import VueInput from '../components/VueInput.vue';
  import VueInputError from '../components/VueInputError.vue';
  import VueLabel from '../components/VueLabel.vue';

  export default {
    name: 'view-lost-password',

    data() {
      return {
        loading: false,
        shake: false,
        email: ''
      }
    },

    validations: {
      email: {
        required,
        email: email
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
          this.loading = true;
          this.submit().finally(() => {
            this.loading = false;
          });
        }
      },

      submit() {
        return this.$store.dispatch('LOST_PASSWORD', {
          email: this.email
        }).then(() => {
          this.$router.push('/lost-password-requested');
        });
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
