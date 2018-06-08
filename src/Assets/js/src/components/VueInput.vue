<template>
  <div class="input" :class="inputWrapperClasses">
    <input
      :class="inputClasses"
      :id="id"
      :name="id"
      :type="type"
      :value="value"
      :placeholder="placeholder"
      @change="onChange($event.target.value)"
      @input="onInput($event.target.value)"
      autocomplete="off"
      ref="input"
    />
    <div class="input-icon--wrapper" v-if="icon"><i :class="iconClasses" aria-hidden="true"></i></div>
  </div>
</template>

<script>
  export default {
    name: 'vue-input',

    props: {
      value: {
        type: [String, Number],
        default: ''
      },
      id: {
        type: String,
        required: true
      },
      model: {
        required: false
      },
      type: {
        type: String,
        required: true
      },
      placeholder: {
        type: String,
        required: false,
        default: ''
      },
      icon: {
        type: String,
        required: false
      },
      autofocus: {
        type: Boolean,
        required: false,
        default: false
      },
      error: {
        type: Boolean,
        required: false,
        default: false
      }
    },

    computed: {
      inputWrapperClasses() {
        return {
          'input-error': this.error
        }
      },

      inputClasses() {
        return [
          { 'has-icon': !!this.icon },
          { 'has-error': this.error }
        ]
      },
      iconClasses() {
        return [
          'input-icon',
          `icon-${this.icon}`
        ];
      }
    },

    methods: {
      onChange(value) {
        this.$emit('change', value);
      },

      onInput(value) {
        this.$emit('input', value);
      }
    },

    mounted() {
      if (this.autofocus) {
        this.$refs.input.focus();
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .input {
    position: relative;
  }

  .input-icon--wrapper {
    position: absolute;
    top: 0;
    bottom: 0;
    @include rem(width, 65px);
    display: flex;
    align-items: center;
    justify-content: center;
    @include rem(font-size, 20px);
  }

  .input-icon {
    color: $clr-input-text;
    transition: color 0.15s linear;

    input:focus + .input-icon--wrapper > & {
      color: $rot;
    }
  }

  input:not([type="button"]) {
    display: block;
    width: 100%;
    @include rem(height, 62px);
    @include rem(padding, 0 30px);
    @include rem(font-size, 18px);
    color: $clr-input-text;
    background: $clr-input-background;
    border: none;
    border-radius: 3px;
    outline: none;

    &.has-icon {
      @include rem(padding, 0 30px 0 65px);
    }

    &.has-error {
      box-shadow: inset -3px -3px 0px $rot, inset 3px 3px 0px $rot;
    }
  }
</style>
