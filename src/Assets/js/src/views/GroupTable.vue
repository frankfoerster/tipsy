<template>
  <div class="view-group-table">
    <group-table :group="group" :key="group.id" v-if="group"></group-table>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import GroupTable from '../components/GroupTable.vue';

  export default {
    name: 'view-group-table',

    components: {
      GroupTable
    },

    computed: {
      ...mapGetters([
        'groupById'
      ]),
      group() {
        if (this.$route.params.groupId) {
          return this.groupById(this.$route.params.groupId);
        }
        return null;
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .view-group-table {
    @include rem(padding-top, 10px);
  }
</style>
