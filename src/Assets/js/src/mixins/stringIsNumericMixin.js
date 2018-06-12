const stringIsNumericMixin = {
  methods: {
    stringIsNumeric(value) {
      if (value === null || typeof value === 'undefined') {
        return false;
      }

      const valueWithoutDigits = '' + value.replace(/[0-9]/g, '');

      return valueWithoutDigits.length === 0;
    }
  }
};

export default stringIsNumericMixin;
