<template>
  <div class="view-table">
    <group-table v-for="group in groups" :group="group" :key="group.id" v-if="groups && groups.length > 0"></group-table>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import GroupTable from '../components/GroupTable.vue';

  export default {
    name: 'view-table',

    components: {
      GroupTable
    },

    computed: {
      ...mapGetters([
        'groups'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
