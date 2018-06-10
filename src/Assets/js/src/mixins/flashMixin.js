const flashMixin = {
  data() {
    return {
      flash: {
        show: false,
        message: '',
        type: ''
      }
    };
  },

  methods: {
    showFlashMessage(message, type) {
      if (typeof message === 'undefined') {
        return;
      }

      this.flash = {
        show: true,
        message: message,
        type: type
      };
    },
  }
};

export default flashMixin;
