const handleErrorsMixin = {
  data() {
    return {
      errors: {}
    }
  },

  methods: {
    setErrors(errors) {
      const fields = Object.keys(errors);
      if (fields.length === 0) {
        return;
      }

      this.errors = errors;
      this.$v.$touch();
    },

    getErrorMessage(field, rule) {
      const errors = this.errors[field];

      if (typeof errors === 'undefined') {
        return '';
      }

      return errors[rule] || '';
    },

    hasError(field, rule) {
      const errors = this.errors[field];

      if (typeof errors === 'undefined') {
        return false;
      }

      return !!errors[rule];
    },

    hasAnyError(field) {
      return (typeof this.errors[field] !== 'undefined');
    },

    focusFirstFormControlWithError() {
      this.$nextTick(() => {
        const formControl = this.$el.querySelectorAll('input.has-error, textarea.has-error, select.has-error').item(0);
        if (formControl === null) {
          return;
        }

        formControl.focus();
      });
    }
  }
};

export default handleErrorsMixin;
