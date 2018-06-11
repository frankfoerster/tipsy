<template>
  <div class="card" :class="[`card__${type}`]">
    <div class="card--container">
      <div class="card--header" v-if="!!this.$slots['title'] || !!this.$slots['link']">
        <div class="card--title" v-if="!!this.$slots['title']">
          <slot name="title"></slot>
        </div>
        <div class="card--link" v-if="!!this.$slots['link']">
          <slot name="link"></slot>
        </div>
      </div>
      <div class="card--content" v-if="!!this.$slots['content']">
        <slot name="content"></slot>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'card',

    props: {
      type: {
        type: String,
        required: true
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .card {
    @include rem(padding, 10px 0 0 10px);
  }

  .card__game {
    @include rem(max-width, 400px);
    @include rem(padding-right);
    margin: 0 auto;
  }

  .card--container {
    @include rem(padding, 15px);
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.08), 0 3px 6px rgba(0,0,0,0.2);
    transition: background-color 0.2s linear;

    .card__game-focused & {
      background-color: #fff6bf;
    }

    .card__game-voting-closed & {
      background-color: #dde9f5;
    }
  }

  .card--header {
    display: flex;
    @include rem(padding-bottom, 10px);
    font-weight: 600;

    .card__group & {
      @include rem(padding-bottom, 15px);
      text-align: center;
    }

    .card__top10 & {
      @include rem(padding-bottom, 14px);
    }
  }

  .card--title {
    width: 100%;
    flex-grow: 1;
  }

  .card--link {
    display: inline-block;
    @include rem(padding, 1px 0);
    @include rem(font-size, 14px);
    white-space: nowrap;
  }
</style>
