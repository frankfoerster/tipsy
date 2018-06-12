<template>
  <div class="vote-winner">
    <form v-if="canVote(firstGame)" class="vote-form" @submit.prevent="onSubmit">
      <vue-label for="vote-winner'">Vote</vue-label>
      <vue-select
        class="vote--control--input"
        v-model.trim="winner"
        id="vote-winner"
        :options="teamsSorted"
        :disabled="loading"
      ></vue-select>
      <vue-button class="vote--button" :loading="loading" :disabled="$v.$invalid || !hasChanged">
        <template v-if="loading">Voting ...</template>
        <template v-if="!loading && !voted">Vote Now!</template>
        <template v-if="!loading && voted">Update Vote!</template>
      </vue-button>
    </form>
    <div class="vote-winner vote-winner__closed" v-else>
      <div v-if="voted" class="vote-winner--voted">{{ teamById(this.winner).name }}</div>
      <div v-else class="vote-winner--not-voted">You did not vote for a winner.</div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { required, integer, minValue } from 'vuelidate/lib/validators';

  import canVote from '../mixins/canVoteMixin';

  import VueButton from './VueButton.vue';
  import VueLabel from './VueLabel.vue';
  import VueSelect from './VueSelect.vue';

  export default {
    name: 'winner-vote',

    mixins: [canVote],

    components: {
      VueButton,
      VueLabel,
      VueSelect
    },

    data() {
      return {
        loading: false,
        initialWinner: '',
        winner: '',
        voted: false
      }
    },

    validations() {
      return {
        winner: {
          required,
          integer,
          minValue: minValue(1)
        }
      }
    },

    computed: {
      ...mapGetters([
        'firstGame',
        'teamsSorted',
        'teamById',
        'user',
        'token'
      ]),

      hasChanged() {
        return (parseInt(this.initialWinner) !== parseInt(this.winner));
      }
    },

    methods: {
      onSubmit(event) {
        this.loading = true;

        const vote = Object.assign({}, {
          winning_team_id: this.winner
        });

        setTimeout(() => {
          this.$store.dispatch('VOTE_WINNER', {
            token: this.token,
            vote: vote
          }).then((data) => {
            this.initialWinner = vote.winner;
            this.voted = true;

            if (data && data.message) {
              this.$notify({
                group: 'vote',
                title: 'Success!',
                text: data.message,
                type: 'success',
                duration: 6000
              });
            }
          }).catch((data) => {
            let errorMsg = '';

            if (data.message) {
              errorMsg = data.message;
            }

            this.$notify({
              group: 'vote',
              title: 'Error!',
              text: errorMsg,
              type: 'error'
            });
          }).finally(() => {
            this.loading = false;
          });
        }, 200)
      },
    },

    mounted() {
      const user = this.user;

      this.initialWinner = '' + user.winning_team_id;
      this.winner = '' + user.winning_team_id;

      if (user.winning_team_id !== null) {
        this.voted = true;
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .vote-winner {

    .vote--button {
      @include rem(margin-top, 12px);
    }
  }

  .vote-winner__closed {
    cursor: default;
  }

  .vote-winner--voted {
    @include rem(padding, 15px 0 10px);
    @include rem(font-size, 20px);
    font-weight: 600;
    text-align: center;
  }

  .vote-winner--not-voted {
    font-weight: 600;
    color: $rot2;
  }
</style>
