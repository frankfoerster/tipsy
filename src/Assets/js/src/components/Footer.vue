<template>
  <footer class="footer">
    <router-link class="footer--link" to="imprint">Imprint</router-link>
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
    @include rem(padding, 10px);
    text-align: center;
  }

  .footer--link:hover {
    text-decoration: underline;
  }
</style>
