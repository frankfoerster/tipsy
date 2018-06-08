import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({

  /* --------------------------------------------------------------------------------------------- */
  /* ------------------------------------- INITIAL STATE ----------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  state: {
    appTitle: global.window.AppConfig.appTitle || 'BETZ',
    user: null,
    games: {},
    tips: {}
  },

  /* --------------------------------------------------------------------------------------------- */
  /* ---------------------------------------- ACTIONS -------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  actions: {

  },

  /* --------------------------------------------------------------------------------------------- */
  /* --------------------------------------- MUTATIONS ------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  mutations: {

  },

  /* --------------------------------------------------------------------------------------------- */
  /* ---------------------------------------- GETTERS -------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  getters: {
    appTitle(state) {
      return state.appTitle;
    }
  }

});

export default store;
