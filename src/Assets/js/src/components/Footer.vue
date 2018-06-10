<template>
  <footer class="footer">
    <router-link class="footer--link" to="imprint">Imprint</router-link>
    <span class="made-with-love">Made with <span class="heart">❤</span> by <a class="twitter-handle" href="https://twitter.com/frfoerster">@frfoerster</a></span>
  </footer>
</template>

<script>
  export default {
    name: 'app-footer'
  };
</script>

<style lang="scss">
  @import '../sass/imports';

  .footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    @include rem(padding, 10px);
    background-color: #fff;
    border-top: 1px solid #e6e6e6;

    @include respond-to-min(500px) {
      text-align: center;
    }
  }

  .footer--link:hover {
    text-decoration: underline;
  }

  .made-with-love {
    position: absolute;
    @include rem(top, 14px);
    @include rem(right, 20px);
    display: inline-block;
    font-size: 12px;
    @include rem(margin-left, 10px);

    .heart {
      display: inline-block;
      @include rem(font-size, 16px);
      line-height: 0;
      color: #e25555;
      transform: translateY(1px);
    }

    .twitter-handle {
      color: #1F98C7;
    }
  }
</style>
