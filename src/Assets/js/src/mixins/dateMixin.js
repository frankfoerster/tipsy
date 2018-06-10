import moment from 'moment';

const dateMixin = {
  methods: {
    formatDate(date, format) {
      return moment(date).format(format);
    }
  }
};

export default dateMixin;
