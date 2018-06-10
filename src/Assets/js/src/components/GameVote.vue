<template>
  <div class="vote">
    <form class="vote-form" @submit.prevent="onSubmit">
      <div class="vote--control">
        <vue-label :for="'vote-' + game.id + '-v1'">Vote</vue-label>
        <vue-input
          class="vote--control--input"
          v-model.trim="result1"
          :id="'vote-' + game.id + '-v1'"
          type="number"
          placeholder="Your Vote"
          @focus="onFocus($event)"
          @blur="onBlur($event)"
          @keydown="onKeydown"
          :error="$v.result1.$invalid"
        ></vue-input>
      </div>
      <div class="vote--spacer">:</div>
      <div class="vote--control">
        <vue-label :for="'vote-' + game.id + '-v2'">Vote</vue-label>
        <vue-input
          class="vote--control--input"
          v-model.trim="result2"
          :id="'vote-' + game.id + '-v2'"
          type="number"
          placeholder="Your Vote"
          @focus="onFocus($event)"
          @blur="onBlur($event)"
          @keydown="onKeydown"
          :error="$v.result2.$invalid"
        ></vue-input>
      </div>
      <vue-button class="vote--button" :loading="loading" :disabled="$v.$invalid || !hasChanged">
        <template v-if="loading">Voting ...</template>
        <template v-if="!loading && !voted">Vote Now!</template>
        <template v-if="!loading && voted">Update Vote!</template>
      </vue-button>
    </form>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { required, integer, minValue } from 'vuelidate/lib/validators';

  import VueLabel from './VueLabel.vue';
  import VueInput from './VueInput.vue';
  import VueButton from './VueButton.vue';

  export default {
    name: 'game-vote',

    props: {
      game: {
        type: Object,
        required: true
      }
    },

    components: {
      VueButton,
      VueInput,
      VueLabel
    },

    data() {
      return {
        loading: false,
        initialResult1: '',
        initialResult2: '',
        result1: '',
        result2: '',
        voted: false
      }
    },

    validations() {
      return {
        result1: {
          required,
          integer,
          minValue: minValue(0)
        },
        result2: {
          required,
          integer,
          minValue: minValue(0)
        }
      }
    },

    computed: {
      ...mapGetters([
        'userTipByGameId',
        'token'
      ]),

      hasChanged() {
        return (
          this.initialResult1 !== this.result1 ||
          this.initialResult2 !== this.result2
        );
      }
    },

    methods: {
      onBlur(event) {
        this.$emit('voteBlur', event);
      },

      onFocus(event) {
        this.$emit('voteFocus', event);
      },

      onKeydown (event) {
        if (
          // Allow: backspace, delete, tab, escape, enter
          [8, 46, 9, 27, 13].indexOf(event.keyCode) !== -1 ||
          // Allow: Ctrl+A
          (event.keyCode === 65 && event.ctrlKey === true) ||
          // Allow: Ctrl+C
          (event.keyCode === 67 && event.ctrlKey === true) ||
          // Allow: Ctrl+V
          (event.keyCode === 86 && event.ctrlKey === true) ||
          // Allow: Ctrl+X
          (event.keyCode === 88 && event.ctrlKey === true) ||
          // Allow: home, end, left, right
          (event.keyCode >= 35 && event.keyCode <= 39)
        ) {
          return;
        }

        if (event.shiftKey ||
          (
            // Allow: num keys
            !(event.keyCode >= 48 && event.keyCode <= 57) &&
            // Allow: numpad keys
            !(event.keyCode >= 96 && event.keyCode <= 105)
          )
        ) {
          event.preventDefault();
        }
      },

      onSubmit(event) {
        this.loading = true;

        const vote = Object.assign({}, {
          gameId: this.game.id,
          result1: this.result1,
          result2: this.result2
        });

        setTimeout(() => {
          this.$store.dispatch('VOTE', {
            token: this.token,
            vote: vote
          }).then((data) => {
            this.initialResult1 = vote.result1;
            this.initialResult2 = vote.result2;
            this.voted = true;

            if (data && data.message) {
              this.$notify({
                group: 'vote',
                title: 'Vote submitted!',
                text: data.message,
                duration: 1000000
              });
            }
          }).catch(() => {

          }).finally(() => {
            this.loading = false;
          });
        }, 200)
      }
    },

    mounted() {
      const userTip = this.userTipByGameId(this.game.id);
      if (userTip.voted) {
        this.initialResult1 = '' + userTip.result1;
        this.initialResult2 = '' + userTip.result2;
        this.result1 = '' + userTip.result1;
        this.result2 = '' + userTip.result2;
        this.voted = true;
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .vote {
    @include rem(padding-bottom, 10px);
  }

  .vote-form {
    display: flex;
    flex-wrap: wrap;
  }

  .vote--control {
    width: calc((100% - 56px)/2);
  }

  .vote--control--input {

    input {
      @include rem(height, 42px);
      @include rem(padding, 0 10px);
      -moz-appearance: textfield;
      color: #000;
      font-weight: 600;
      background-color: #fafafa;
      border: 1px solid #b5b5b5;

      &::-webkit-outer-spin-button,
      &::-webkit-inner-spin-button {
        -webkit-appearance: none;
      }

      &::placeholder {
        color: #a1a1a1;
        font-weight: 400;
      }

      .vote--control ~ .vote--control & {
        text-align: right;
      }

      &.has-error {
        box-shadow: inset -2px -2px 0px #c21429, inset 2px 2px 0px #c21429;
      }

      @include respond-to-min(400px) {
        @include rem(padding, 0 20px);
      }

      @include respond-to-min-max(640px, 699px) {
        @include rem(padding, 0 10px);
      }
    }
  }

  .vote--spacer {
    display: flex;
    @include rem(width, 56px);
    align-items: center;
    justify-content: center;
  }

  .vote--button {
    @include rem(margin-top, 10px);
    width: 100%;
    @include rem(padding, 12px 15px);
  }
</style>