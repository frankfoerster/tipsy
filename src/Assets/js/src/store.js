import moment from 'moment';
import 'moment/locale/de';
import Vue from 'vue';
import Vuex from 'vuex';

import userResource from './api/userResource';
import tipResource from './api/tipResource';

import period from './util/period';

Vue.use(Vuex);

const store = new Vuex.Store({

  /* --------------------------------------------------------------------------------------------- */
  /* ------------------------------------- INITIAL STATE ----------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  state: {
    appTitle: global.window.AppConfig.appTitle || 'BETZ',
    user: null,
    token: global.window.localStorage.getItem('token'),
    games: global.window.AppConfig.games || {},
    groups: global.window.AppConfig.groups || {},
    teams: global.window.AppConfig.teams || {},
    ranking: /*global.window.AppConfig.ranking*/null || {
      1: {
        total_points: 3,
        username: 'fo1'
      },
      2: {
        total_points: 3234,
        username: 'fo2'
      },
      3: {
        total_points: 33,
        username: 'fo3'
      },
      4: {
        total_points: 34,
        username: 'fo4'
      },
      5: {
        total_points: 13,
        username: 'fo5'
      },
      6: {
        total_points: 33,
        username: 'fo6'
      },
      7: {
        total_points: 35,
        username: 'fo7'
      },
      8: {
        total_points: 34,
        username: 'fo8'
      },
      9: {
        total_points: 37,
        username: 'fo9'
      },
      10: {
        total_points: 32,
        username: 'fo10'
      },
      11: {
        total_points: 33,
        username: 'fo11'
      },
      12: {
        total_points: 36,
        username: 'fo12'
      },
      13: {
        total_points: 37,
        username: 'fo13'
      },
      14: {
        total_points: 23,
        username: 'fo14'
      },
      15: {
        total_points: 83,
        username: 'fo15'
      }
    },
    tips: {},
    redirect: null
  },

  /* --------------------------------------------------------------------------------------------- */
  /* ---------------------------------------- ACTIONS -------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  actions: {
    SIGNUP({}, user) {
      return userResource.signup(user);
    },

    LOGIN({commit, dispatch}, credentials) {
      return userResource.login(credentials)
        .then(data => dispatch('PERSIST_TOKEN', data))
        .then(data => commit('SET_USER', data.user));
    },

    LOGOUT({commit, dispatch}, token) {
      return userResource.logout(token)
        .then(() => {
          return dispatch('RESET_USER');
        });
    },

    LOGIN_REDIRECT({commit}, {to, next}) {
      commit('SET_LOGIN_REDIRECT', to);
      next('/login');
    },

    FORGET_LOGIN_REDIRECT({commit}) {
      commit('SET_LOGIN_REDIRECT', null);
    },

    RESET_USER({commit}) {
      commit('SET_USER', null);
      commit('SET_TOKEN', null);

      return Promise.resolve();
    },

    PERSIST_TOKEN({commit}, data) {
      const auth = data.auth;

      if (typeof data.auth === 'undefined' || typeof data.auth.token === 'undefined') {
        const error = {
          message: 'Authentication failed.',
          originalData: data
        };
        return Promise.reject(error);
      }

      commit('SET_TOKEN', data.auth.token);

      return Promise.resolve(data);
    },

    FETCH_USER_INFO({commit}, token) {
      return userResource.info(token)
        .then(data => commit('SET_USER', data.user));
    },

    VERIFY_EMAIL({commit}, token) {
      return userResource.verify(token);
    },

    REQUEST_VERIFICATION_EMAIL({commit}, token) {
      return userResource.requestVerificationEmail(token);
    },

    VOTE({commit}, {token, vote}) {
      return tipResource.createOrUpdate(token, vote)
        .then((data) => {
          commit('UPDATE_USER_TIP', Object.assign({}, vote, { voted: true }));

          return Promise.resolve(data);
        });
    }
  },

  /* --------------------------------------------------------------------------------------------- */
  /* --------------------------------------- MUTATIONS ------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  mutations: {
    SET_USER(state, user) {
      state.user = user;
    },

    SET_TOKEN(state, token) {
      global.window.localStorage.setItem('token', token);
      state.token = token;
    },

    DELETE_TOKEN(state) {
      global.window.localStorage.removeItem('token');
      state.token = null;
    },

    SET_LOGIN_REDIRECT(state, to) {
      state.redirect =  to && to.fullPath || null;
    },

    UPDATE_USER_TIP(state, vote) {
      Vue.set(state.user.tips, vote.game_id, vote);
    }
  },

  /* --------------------------------------------------------------------------------------------- */
  /* ---------------------------------------- GETTERS -------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  getters: {
    appTitle(state) {
      return state.appTitle;
    },

    games(state) {
      const games = Object.keys(state.games).map(key => { return state.games[key] });

      return [...games].sort((a, b) => {
        if (a.playing_timestamp > b.playing_timestamp) { return 1; }
        if (a.playing_timestamp < b.playing_timestamp) { return -1; }
        return 0;
      });
    },

    groups(state) {
      const groups = Object.keys(state.groups).map(key => { return state.groups[key] });

      return [...groups].sort((a, b) => {
        if (a.name > b.name) { return 1; }
        if (a.name < b.name) { return -1; }
        return 0;
      });
    },

    icons(state) {
      return Object.keys(state.teams).map(key => { return state.teams[key].icon });
    },

    isAuthenticated(state) {
      return state.user !== null && state.token !== null;
    },

    notVerified(state) {
      if (!state.user) {
        return false;
      }
      return !state.user.verified;
    },

    periodLastSixteen(state, getters) {
      const games = [...getters.games].filter((game) => { return game.is_last_sixteen; });
      const firstGame = games[0];
      const lastGame = games[games.length - 1];

      return period(moment(firstGame.playing_time), moment(lastGame.playing_time), 'DD.MM.');
    },

    periodQuarterFinal(state, getters) {
      const games = [...getters.games].filter((game) => { return game.is_quarter_final; });
      const firstGame = games[0];
      const lastGame = games[games.length - 1];

      return period(moment(firstGame.playing_time), moment(lastGame.playing_time), 'DD.MM.');
    },

    periodSemiFinal(state, getters) {
      const games = [...getters.games].filter((game) => { return game.is_semi_final; });
      const firstGame = games[0];
      const lastGame = games[games.length - 1];

      return period(moment(firstGame.playing_time), moment(lastGame.playing_time), 'DD.MM.');
    },

    period3rdPlace(state, getters) {
      const games = [...getters.games].filter((game) => { return game.is_game_for_3rd_place; });
      const game = games[0];

      return period(moment(game.playing_time), null, 'DD.MM.');
    },

    periodFinal(state, getters) {
      const games = [...getters.games].filter((game) => { return game.is_final; });
      const game = games[0];

      return period(moment(game.playing_time), null, 'DD.MM.');
    },

    redirect(state) {
      return state.redirect;
    },

    teamById: (state) => (id) => {
      return state.teams[id];
    },

    teams(state) {
      return Object.keys(state.teams).map(key => { return state.teams[key] });
    },

    teamsById: (state, getters) => (ids) => {
      return [...getters.teams].filter(team => {
        return ids.indexOf(team.id) !== -1;
      });
    },

    token(state) {
      return state.token;
    },

    user(state) {
      return state.user;
    },

    ranking(state) {
      return Object.keys(state.ranking).map(key => { return state.ranking[key] });
    },

    top10(state, getters) {
      return [...getters.ranking].sort((a, b) => {
        if (a.total_points < b.total_points) { return 1; }
        if (a.total_points > b.total_points) { return -1; }
        return 0;
      });
    },

    upcomingGames(state, getters) {
      const currentTimestamp = moment().unix();

      return [...getters.games].filter(game => {
        return game.playing_timestamp > currentTimestamp;
      });
    },

    userTipByGameId: (state) => (id) => {
      return state.user.tips[id];
    }
  }

});

export default store;
