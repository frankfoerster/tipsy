import moment from 'moment';

const canVoteMixin = {
  methods: {
    canVote(game) {
      return (((game.playing_timestamp - moment().unix()) / 60) > 15);
    }
  }
};

export default canVoteMixin;
