import Vue from 'vue';

const focusableMixin = {
  data() {
    return {
      isFocused: {}
    }
  },

  methods: {
    onFocus(gameId) {
      Vue.set(this.isFocused, gameId, true);
    },

    onBlur(gameId) {
      Vue.set(this.isFocused, gameId, false);
    }
  }
};

export default focusableMixin;
