<template>
  <div class="select" :class="selectWrapperClasses">
    <select
      :class="selectClasses"
      :id="id"
      @change="onChange($event.target.value)"
      @input="onInput($event.target.value)"
      @focus="onFocus($event)"
      @blur="onBlur($event)"
      autocomplete="off"
      :disabled="disabled"
      ref="select"
    >
      <option>Choose your winner ...</option>
      <option v-for="option in options" :key="option.id" :value="option.id" v-if="options && options.length > 0" :selected="parseInt(value) === option.id">{{ option.name }}</option>
    </select>
    <div class="select-icon--wrapper"><i :class="iconClasses" aria-hidden="true"></i></div>
  </div>
</template>

<script>
  export default {
    name: 'vue-select',

    props: {
      value: {
        type: [String, Number],
        default: ''
      },
      id: {
        type: String,
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      error: {
        type: Boolean,
        required: false,
        default: false
      },
      disabled: {
        type: Boolean,
        required: false,
        default: false
      }
    },

    computed: {
      selectWrapperClasses() {
        return {
          'select-error': this.error
        }
      },

      selectClasses() {
        return [
          { 'has-error': this.error }
        ]
      },

      iconClasses() {
        return [
          'select-icon',
          'icon-angle-down'
        ];
      }
    },

    methods: {
      onChange(value) {
        this.$emit('change', value);
      },

      onInput(value) {
        this.$emit('input', value);
      },

      onFocus(event) {
        this.$emit('focus', event);
      },

      onBlur(event) {
        this.$emit('blur', event);
      },

      focus() {
        this.$refs.select.focus();
      }
    },

    mounted() {
      if (this.autofocus) {
        this.focus();
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .select {
    width: 100%;
    position: relative;
  }

  .select-icon--wrapper {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    @include rem(width, 50px);
    display: flex;
    align-items: center;
    justify-content: center;
    @include rem(font-size, 20px);
  }

  .select-icon {
    color: $clr-input-text;
    transition: color 0.15s linear;

    select:focus + .select-icon--wrapper > & {
      color: $rot;
    }
  }

  select {
    display: block;
    width: 100%;
    @include rem(height, 42px);
    @include rem(padding, 0 60px 0 30px);
    @include rem(font-size, 18px);
    background-color: #fafafa;
    border: 1px solid #b5b5b5;
    color: #000;
    font-weight: 600;
    border-radius: 3px;
    outline: none;
    -moz-appearance: none;
    -webkit-appearance: none;

    &::-ms-expand {
      display: none;
    }

    &.has-error {
      box-shadow: inset -3px -3px 0px $rot, inset 3px 3px 0px $rot;
    }
  }
</style>
