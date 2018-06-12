<template>
  <div class="top-ten">
    <div v-for="(user, index) in top10" :key="user.id" v-if="top10 && top10.length > 0 && index < 10" class="top-ten--row">
      <div class="top-ten--place">{{ index + 1 }}.</div>
      <div class="top-ten--name">{{ user.username }}</div>
      <div class="top-ten--points">{{ user.total_points }}</div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'top-ten',

    computed: {
      ...mapGetters([
        'top10'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .top-ten--row {
    display: flex;
    @include rem(margin, 0 -15px -1px);
    @include rem(padding, 9px 15px 6px);
    border-bottom: 1px solid #f5f5f5;
    cursor: default;
    transition: background-color 0.2s linear;

    &:last-of-type {
      border-bottom: none;
    }

    &:hover {
      background-color: #f5f5f5;
    }
  }

  .top-ten--place {
    width: 30px;
    text-align: right;
  }

  .top-ten--name {
    width: 100%;
    @include rem(padding, 0 10px);
    flex-grow: 1;
  }

  .top-ten--points {
    @include rem(width, 50px);
    text-align: right;
  }
</style>
