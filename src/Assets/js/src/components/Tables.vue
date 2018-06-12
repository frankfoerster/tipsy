<template>
  <div class="tables">
    <group-table v-for="group in getGroups" :key="group.id" :group="group" v-if="getGroups"></group-table>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import stringIsNumeric from '../mixins/stringIsNumericMixin';

  import GroupTable from '../components/GroupTable.vue';

  export default {
    name: 'tables',

    mixins: [stringIsNumeric],

    components: {
      GroupTable
    },

    computed: {
      ...mapGetters([
        'groups',
        'groupById'
      ]),

      getGroups() {
        const groupId = this.$route.params.groupId;

        if (this.stringIsNumeric(groupId)) {
          return [this.groupById(groupId)];
        }

        return this.groups;
      }
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';
</style>
