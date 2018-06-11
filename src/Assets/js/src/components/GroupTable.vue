<template>
  <card type="table" v-if="group">
    <template slot="content">
      <table class="group-table">
        <thead>
        <tr class="group-table--row group-table--row__header">
          <th class="group-table--cell group-table--cell__header" colspan="2">Group {{ group.name }}</th>
          <th class="group-table--cell group-table--cell__header" title="Games">G</th>
          <th class="group-table--cell group-table--cell__header" title="Goal Difference">D</th>
          <th class="group-table--cell group-table--cell__header" title="Points">P</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(team, index) in tableByTeamIds(group.teams)" v-if="group.teams && group.teams.length > 0" class="group-table--row group-table--row__body">
          <td class="group-table--cell group-table--cell__place">{{ index + 1 }}</td>
          <td class="group-table--cell group-table--cell__team">
            <div class="group-table--team">
              <div class="group-table--team--image-wrapper">
                <img :src="baseUrl + team.team.icon" :title="team.team.name" :alt="team.team.name" class="group-table--team--image">
              </div>
              <div class="group-table--team--name">
                {{ team.team.name }}
              </div>
            </div>
          </td>
          <td class="group-table--cell group-table--cell__games">{{ team.stats.games }}</td>
          <td class="group-table--cell group-table--cell__diff">{{ team.stats.diff }}</td>
          <td class="group-table--cell group-table--cell__points">{{ team.stats.points }}</td>
        </tr>
        </tbody>
      </table>
    </template>
  </card>
</template>

<script>
  import {mapGetters} from 'vuex';

  import Card from '../components/Card.vue';

  export default {
    name: 'group-table',

    components: {
      Card
    },

    props: {
      group: {
        type: Object,
        required: false,
        default: null
      }
    },

    computed: {
      ...mapGetters([
        'baseUrl',
        'tableByTeamIds'
      ])
    }
  }
</script>

<style lang="scss">
  @import '../sass/imports';

  .group-table {
    width: 100%;
    @include rem(margin, 0 -15px);
  }

  .group-table--row {

    .group-table--cell:first-child {
      @include rem(padding-left, 15px);
    }

    .group-table--cell:last-child {
      @include rem(padding-right, 15px);
    }
  }

  .group-table--cell__header {
    text-align: right;
    cursor: help;

    &:first-child {
      @include rem(padding-left, 15px);
      text-align: left;
      cursor: default;
    }

    &:last-child {
      @include rem(padding-right, 15px);
    }
  }

  .group-table--row__body {

    .group-table--cell {
      border-bottom: 1px solid #f5f5f5;
    }

    &:last-of-type {

      .group-table--cell {
        border-bottom: none;
      }
    }
  }

  .group-table--cell {
    @include rem(padding, 5px);
  }

  .group-table--cell__games,
  .group-table--cell__diff,
  .group-table--cell__points {
    text-align: right;
  }

  .group-table--cell__name {
    width: 100%;
  }

  .group-table--cell__games,
  .group-table--cell__diff {
    @include rem(min-width, 40px);
  }

  .group-table--cell__points {
    @include rem(min-width, 50px);
    font-weight: 600;
  }

  .group-table--team {
    display: flex;
    align-items: center;
  }

  .group-table--team--image-wrapper {
    @include rem(width, 30px);
    @include rem(height, 30px);
  }

  .group-table--team--image {
    width: 100%;
    max-width: 100%;
    height: auto;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.2);
  }

  .group-table--team--name {
    @include rem(padding-left, 10px);
  }
</style>
