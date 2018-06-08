<template>
  <div class="user-menu">
    <a class="user-menu--dropdown--toggle" href="javascript:void(0)" @click.prevent="openMenu">{{ username }} <i class="icon-angle-down" aria-hidden="true"></i></a>
    <transition name="fade">
      <ul class="user-menu--dropdown">
        <li class="user-menu--dropdown--item">
          <a href="javascript:void(0)" class="user-menu--dropdown--link" @click.prevent="logout">Logout</a>
        </li>
      </ul>
    </transition>
  </div>
</template>

<script>
  import UserMenu from './UserMenu.vue';

  export default {
    name: 'user-menu',

    components: {
      UserMenu
    },

    computed: {
      username() {
        return 'admin';
      }
    },

    methods: {
      openMenu() {
        // TODO
      },

      logout() {
        // TODO
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .user-menu--dropdown {
    display: none;
  }

  .user-menu--dropdown--toggle {
    display: inline-block;
    @include rem(padding, 13px 10px 12px);
    color: #fff;
    text-decoration: none;
    transition: background-color 0.2s linear;

    &:hover {
      background-color: $rot2;
      text-decoration: none;
    }

    i {
      margin-right: 0;
    }
  }
</style>
