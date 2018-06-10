<template>
  <div class="group-teams">
    <div v-for="team in teams" :key="team.id" v-if="teams && teams.length > 0" class="group-teams--team">
      <img :src="team.icon" :title="team.name" :alt="team.name" class="group-teams--image">
    </div>
  </div>
</template>

<script>
  export default {
    name: 'group-teams',

    props: {
      teams: {
        type: Array,
        required: true
      }
    }
  };
</script>

<style lang="scss">
  @import '../sass/imports';

  .group-teams {
    display: flex;
    justify-content: space-between;
    @include rem(padding-bottom, 12px);

    @include respond-to-min-max(480px, 639px) {
      @include rem(padding-bottom, 15px);
    }

    @include respond-to-min-max(900px, 1279px) {
      @include rem(padding-bottom, 15px);
    }
  }

  .group-teams--team {
    width: 20%;
  }

  .group-teams--image {
    width: 100%;
    max-width: 100%;
    height: auto;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.2);
  }
</style>
