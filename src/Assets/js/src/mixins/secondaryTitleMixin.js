import { mapGetters } from 'vuex';

import stringIsNumeric from './stringIsNumericMixin';

const secondaryTitleMixin = {

  mixins: [stringIsNumeric],

  computed: {
    ...mapGetters([
      'groupById'
    ])
  },

  methods: {
    getSecondaryTitle(target) {
      if (this.stringIsNumeric(target) && this.groupById(target)) {
        return 'Group ' + this.groupById(target).name;
      }

      if (target === 'last-sixteen') {
        return 'Last Sixteen';
      }

      if (target === 'quarter-final') {
        return 'Quarter Final';
      }

      if (target === 'semi-final') {
        return 'Semi Final';
      }

      if (target === '3rd-place') {
        return '3rd Place';
      }

      if (target === 'final') {
        return 'Final';
      }

      return false;
    }
  }
};

export default secondaryTitleMixin;
