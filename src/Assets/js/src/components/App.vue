<template>
  <div class="wrapper">
    <app-header></app-header>
    <div class="content">
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
    <app-footer></app-footer>
  </div>
</template>

<script>
  import AppHeader from './Header.vue';
  import AppFooter from './Footer.vue';

  export default {
    name: 'app',

    components: {
      AppHeader,
      AppFooter
    }
  };
</script>

<style lang="scss">
  @import '../../node_modules/normalize.css/normalize.css';
  @import '../sass/imports';
  @import '../sass/icons';

  :root {
    -ms-overflow-style: -ms-autohiding-scrollbar;
  }

  *, *::before, *::after {
    box-sizing: border-box;
  }

  html, body, .wrapper {
    height: 100%;
    min-height: 100%;
  }

  .wrapper {

    &:before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: transparent url(../img/bg/3.jpg) no-repeat 50% 50%;
      background-size: cover;
      z-index: $zindex-bg-image;
    }
  }

  .content {
    @include rem(padding-top, 44px);
    min-height: calc(100vh - 38px);
  }

  body {
    font-family: $body-font-family;
    @include rem(font-size, $body-font-size);
    background-color: #fff;
  }

  a {
    color: $link-color;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }

  .sr-only {
    @include visuallyhidden();
  }

  .fade-enter {
    opacity: 0;
    pointer-events: none;
    user-select: none;
  }

  .fade-enter-active {
    pointer-events: none;
    user-select: none;
    transition: opacity .3s ease;
  }

  .fade-leave {
    pointer-events: none;
    user-select: none;
  }

  .fade-leave-active {
    pointer-events: none;
    user-select: none;
    transition: opacity .3s ease;
    opacity: 0;
  }
</style>
