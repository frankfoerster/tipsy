<template>
  <div class="user-menu" :class="containerClasses">
    <transition name="fade">
      <div v-if="isAuthenticated">
        <a class="user-menu--dropdown--toggle" href="javascript:void(0)" @click.prevent="toggleDropdown">{{ username }} <i class="icon-angle-down" aria-hidden="true"></i></a>
        <transition name="fade">
          <div class="user-menu--dropdown" ref="dropdown" v-if="dropdownOpen">
            <ul class="user-menu--items">
              <li class="user-menu--item">
                <router-link to="/profile" class="user-menu--link">Mein Profil</router-link>
              </li>
              <li class="user-menu--item">
                <router-link to="/logout" class="user-menu--link">Logout</router-link>
              </li>
            </ul>
          </div>
        </transition>
      </div>
    </transition>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import dropdown from '../mixins/dropdownMixin';

  import UserMenu from './UserMenu.vue';

  export default {
    name: 'user-menu',

    mixins: [dropdown],

    components: {
      UserMenu
    },

    computed: {
      ...mapGetters([
        'user',
        'isAuthenticated'
      ]),

      username() {
        return this.user.username;
      },

      containerClasses() {
        return {
          'user-menu__open': this.dropdownOpen
        }
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .user-menu {
    display: none;
    position: relative;

    @include respond-to-min($breakpoint-display-full-menu) {
      display: block;
    }
  }

  .dropdown-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: $zindex-dropdown-backdrop;
  }

  .user-menu--dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    @include rem(min-width, 170px);
    z-index: $zindex-dropdown;
  }

  .user-menu--dropdown--toggle {
    display: inline-block;
    @include rem(padding, 13px 10px 12px);
    color: #fff;
    text-decoration: none;
    transition: background-color 0.2s linear;

    &:hover {
      background-color: $rot3;
      text-decoration: none;
    }

    i {
      margin-right: 0;
    }

    .user-menu__open & {
      background-color: $rot3;
    }
  }

  .user-menu--items {
    list-style: none;
    margin: 0;
    @include rem(padding, 10px 0);
    background-color: #fff;
    border-radius: 0 0 3px 3px;
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
  }

  .user-menu--link {
    display: block;
    @include rem(padding, 10px 20px);
    transition: background-color 0.2s linear;

    &:hover, &:focus {
      background-color: #f7f7f7;
      text-decoration: none;
    }
  }
</style>
