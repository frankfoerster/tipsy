<template>
  <header class="header">
    <container class="header--container">
      <div class="header--title"><router-link to="/" class="header--title--link" exact><i class="icon-soccer-ball header--title--icon" aria-hidden="true"></i>{{ appTitle }}</router-link></div>
      <main-menu></main-menu>
      <user-menu></user-menu>
    </container>
  </header>
</template>

<script>
  import { mapGetters } from 'vuex';

  import Container from './Container.vue';
  import MainMenu from './MainMenu.vue';
  import UserMenu from './UserMenu.vue';

  export default {
    name: 'app-header',

    components: {
      Container,
      MainMenu,
      UserMenu
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

  .container.header--container {
    padding: 0;
  }

  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #c21429;
    z-index: $zindex-header;
  }

  .header--container {
    display: flex;
    align-items: center;
  }

  .header--title {
    @include rem(padding, 10px 30px 10px 10px);
    width: 100%;
    flex-grow: 1;
    @include rem(font-size, 20px);
    user-select: none;

    @include respond-to-min($breakpoint-display-full-menu) {
      flex-grow: unset;
      width: auto;
    }
  }

  .header--title--link {
    color: #fff;

    &, &:hover, &:focus {
      text-decoration: none;
    }
  }

  .header--title--icon {
    transform: translateY(2px);
  }
</style>
