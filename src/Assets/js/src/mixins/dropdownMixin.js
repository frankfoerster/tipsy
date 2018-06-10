const dropdownMixin = {
  data() {
    return {
      dropdownOpen: false
    }
  },

  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },

    closeDropdown() {
      this.dropdownOpen && this.toggleDropdown();
    },

    onDocumentClick(event) {
      const el = this.$refs.dropdown;
      const target = event.target;

      if (typeof el === 'undefined' || !this.dropdownOpen) {
        return;
      }

      if (
        el !== target && !el.contains(target) ||
        el !== target && el.contains(target) && typeof target.__vue__ !== 'undefined' && target.__vue__.$vnode.componentOptions.tag === 'router-link'
      ) {
        this.closeDropdown();
      }
    }
  },

  created () {
    document.addEventListener('click', this.onDocumentClick)
  },

  destroyed () {
    document.removeEventListener('click', this.onDocumentClick)
  }
};

export default dropdownMixin;
