<template>
  <header class="header">
    <container class="header--container">
      <div class="header--title"><i class="icon-soccer-ball header--title--icon" aria-hidden="true"></i>{{ appTitle }}</div>
      <user-menu></user-menu>
    </container>
  </header>
</template>

<script>
  import { mapGetters } from 'vuex';
  import UserMenu from './UserMenu.vue';
  import Container from './Container.vue';

  export default {
    name: 'app-header',

    components: {
      UserMenu,
      Container
    },

    computed: {
      ...mapGetters([
        'appTitle'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    background-color: #c21429;
    z-index: $zindex-header;
  }

  .header--container {
    display: flex;
    align-items: center;
  }

  .header--title {
    flex-grow: 1;
    @include rem(padding, 10px);
    color: #fff;
    @include rem(font-size, 20px);
    user-select: none;
  }

  .header--title--icon {
    transform: translateY(2px);
  }
</style>
