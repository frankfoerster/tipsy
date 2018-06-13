<template>
  <div class="main-menu" ref="dropdown" :class="{'main-menu__open': this.dropdownOpen}">
    <transition-group name="fade" tag="div">
      <a :key="1" @click.prevent="toggleDropdown" v-if="isAuthenticated" class="main-menu--toggle" href="javascript:void(0)"><i class="icon-menu" aria-hidden="true"></i></a>
      <div :key="2"class="main-menu--items--wrapper">
        <ul v-if="isAuthenticated" v-show="dropdownOpen" class="main-menu--items">
          <li class="main-menu--item">
            <router-link class="main-menu--link" to="/" v-if="$route.name !== null" exact>Overview</router-link>
            <router-link class="main-menu--link" to="/" v-else exact-active-class="router-not-ready" active-class="router-not-ready" exact>Overview</router-link>
          </li>
          <li class="main-menu--item">
            <router-link class="main-menu--link" to="/games">Games</router-link>
          </li>
          <li class="main-menu--item">
            <router-link class="main-menu--link" to="/table">Table</router-link>
          </li>
          <li class="main-menu--item">
            <router-link class="main-menu--link" to="/ranking">Ranking</router-link>
          </li>
          <li class="main-menu--item main-menu--item__user main-menu--item__mobile">
            <div class="main-menu--user--divider"></div>
            <div class="main-menu--user--name">{{ user.username }}</div>
          </li>
          <li class="main-menu--item main-menu--item__mobile">
            <router-link class="main-menu--link" to="/profile">Mein Profil</router-link>
          </li>
          <li class="main-menu--item main-menu--item__mobile">
            <router-link class="main-menu--link" to="/logout">Logout</router-link>
          </li>
        </ul>
      </div>
    </transition-group>
  </div>
</template>

<script>
  import dropdown from '../mixins/dropdownMixin';
  import { mapGetters } from 'vuex';

  export default {
    name: 'main-menu',

    mixins: [dropdown],

    computed: {
      ...mapGetters([
        'isAuthenticated',
        'user'
      ]),
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .main-menu {
    flex-grow: 1;
  }

  .main-menu--items--wrapper {
    position: absolute;
    top: 100%;
    max-height: calc(100vh - 44px);
    right: 0;
    width: 100%;
    overflow-y: scroll;
  }

  .main-menu--items {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .main-menu--item {
    display: block;
  }

  .main-menu--item__user {
    position: relative;
    @include rem(padding-top, 17px);
    font-weight: 600;
    color: #fff;
    background-color: $rot;
    text-align: center;
  }

  .main-menu--user--divider {
    @include rem(margin, 0 16px);
    height: 1px;
    background-color: #fff;
  }

  .main-menu--user--name {
    position: relative;
    top: -9px;
    display: inline-block;
    margin: 0 auto;
    @include rem(padding, 0 20px);
    background-color: $rot;
  }

  .main-menu--link, .main-menu--toggle {
    display: block;
    @include rem(padding, 14px 16px 12px);
    color: #fff;
    background-color: $rot;
    text-decoration: none;
    transition: background-color 0.2s linear;

    &:hover {
      background-color: $rot3;
      text-decoration: none;
    }

    &.router-link-active:not(.router-not-ready) {
      background-color: $rot2;
    }
  }

  .main-menu--toggle {
    @include rem(padding, 13px 16px 12px);

    i {
      margin-right: 0;
    }

    .main-menu__open & {
      background-color: $rot3;
    }
  }

  @include respond-to-min($breakpoint-display-full-menu) {
    .main-menu--items--wrapper {
      position: static;
      top: auto;
      right: 0;
      width: auto;
      overflow-y: auto;
      max-height: none;
    }

    .main-menu--toggle {
      display: none;
    }

    .main-menu--items {
      display: block !important;
      @include clearfix;
    }

    .main-menu--item {
      display: inline-block;
      float: left;
    }

    .main-menu--item__mobile {
      display: none;
    }
  }

</style>
