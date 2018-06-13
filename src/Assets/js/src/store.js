import moment from 'moment';
import 'moment/locale/de';
import Vue from 'vue';
import Vuex from 'vuex';

import userResource from './api/userResource';
import tipResource from './api/tipResource';
import contentResource from './api/contentResource';

import period from './util/period';

Vue.use(Vuex);

const baseUrl = global.window.AppConfig.appBaseUrl || '/';

const store = new Vuex.Store({

  /* --------------------------------------------------------------------------------------------- */
  /* ------------------------------------- INITIAL STATE ----------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  state: {
    appTitle: global.window.AppConfig.appTitle || 'Tipsy',
    baseUrl: baseUrl.endsWith('/') ? baseUrl : baseUrl + '/',
    user: null,
    token: global.window.localStorage.getItem('token'),
    games: global.window.AppConfig.games || {},
    groups: global.window.AppConfig.groups || {},
    teams: global.window.AppConfig.teams || {},
    ranking: global.window.AppConfig.ranking || {},
    tips: {},
    redirect: null,
    imprint: ''
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

    LOST_PASSWORD({commit}, data) {
      return userResource.lostPassword(data);
    },

    RESET_PASSWORD({commit}, data) {
      return userResource.resetPassword(data);
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

    VOTE({commit}, {token, vote, isNew, previousWin, previousDraw, previousLose}) {
      return tipResource.createOrUpdate(token, vote)
        .then((data) => {
          commit('UPDATE_USER_TIP', Object.assign({}, vote, { voted: true }));

          if ((vote.result1 > vote.result2) && !previousWin) {
            commit('INCREASE_WIN', vote.game_id);

            if (!isNew) {
              previousDraw && commit('DECREASE_DRAW', vote.game_id);
              previousLose && commit('DECREASE_LOSE', vote.game_id);
            }
          }

          if ((vote.result1 === vote.result2) && !previousDraw) {
            commit('INCREASE_DRAW', vote.game_id);

            if (!isNew) {
              previousWin && commit('DECREASE_WIN', vote.game_id);
              previousLose && commit('DECREASE_LOSE', vote.game_id);
            }
          }

          if ((vote.result1 < vote.result2) && !previousLose) {
            commit('INCREASE_LOSE', vote.game_id);

            if (!isNew) {
              previousWin && commit('DECREASE_WIN', vote.game_id);
              previousDraw && commit('DECREASE_DRAW', vote.game_id);
            }
          }

          return Promise.resolve(data);
        });
    },

    VOTE_WINNER({commit}, {token, vote}) {
      return userResource.updateWinner(token, vote);
    },

    FETCH_IMPRINT({commit}) {
      return contentResource.imprint()
        .then((data) => {
          commit('SET_IMPRINT', data.imprint);
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
    },

    INCREASE_WIN(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_win: state.games[game_id].times_win + 1 }));
    },

    DECREASE_WIN(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_win: state.games[game_id].times_win - 1 }));
    },

    INCREASE_DRAW(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_draw: state.games[game_id].times_draw + 1 }));
    },

    DECREASE_DRAW(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_draw: state.games[game_id].times_draw - 1 }));
    },

    INCREASE_LOSE(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_lose: state.games[game_id].times_lose + 1 }));
    },

    DECREASE_LOSE(state, game_id) {
      Vue.set(state.games, game_id, Object.assign({}, state.games[game_id], { times_lose: state.games[game_id].times_lose - 1 }));
    },

    SET_IMPRINT(state, imprint) {
      state.imprint = imprint;
    }
  },

  /* --------------------------------------------------------------------------------------------- */
  /* ---------------------------------------- GETTERS -------------------------------------------- */
  /* --------------------------------------------------------------------------------------------- */
  getters: {
    appTitle(state) {
      return state.appTitle;
    },

    baseUrl(state) {
      return state.baseUrl;
    },

    games(state) {
      const games = Object.keys(state.games).map(key => { return state.games[key] });

      return [...games].sort((a, b) => {
        if (a.playing_timestamp > b.playing_timestamp) { return 1; }
        if (a.playing_timestamp < b.playing_timestamp) { return -1; }
        return 0;
      });
    },

    firstGame(state, getters) {
      const games = [...getters.games];

      if (games.length === 0) {
        return null;
      }

      return games[0];
    },

    groups(state) {
      const groups = Object.keys(state.groups).map(key => { return state.groups[key] });

      return [...groups].sort((a, b) => {
        if (a.name > b.name) { return 1; }
        if (a.name < b.name) { return -1; }
        return 0;
      });
    },

    groupById: (state) => (id) => {
      return state.groups[id];
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

    teamsSorted(state, getters) {
      return [...getters.teams].sort(function (a, b) {
        if (a.name > b.name) { return 1; }
        if (a.name < b.name) { return -1; }
        return 0;
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

    rankingSorted(state, getters) {
      return [...getters.ranking].sort((a, b) => {
        if (a.total_points < b.total_points) {
          return 1;
        }

        if (a.total_points > b.total_points) {
          return -1;
        }

        if (a.total_exact < b.total_exact) {
          return 1;
        }

        if (a.total_exact > b.total_exact) {
          return -1;
        }

        if (a.total_tendency < b.total_tendency) {
          return 1;
        }

        if (a.total_tendency > b.total_tendency) {
          return -1;
        }

        if (a.username.toLowerCase() > b.username.toLowerCase()) {
          return 1;
        }

        if (a.username.toLowerCase() < b.username.toLowerCase()) {
          return -1;
        }

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
    },

    teamStats: (state, getters) => (id) => {
      const games = [...getters.games].filter((game) => {
        return (
          game.is_preliminary && game.result1 !== null && game.result2 !== null &&
          (game.team1_id === id  || game.team2_id === id)
        );
      });

      let stats = {
        games: games.length,
        diff: 0,
        points: 0
      };

      games.forEach(game => {
        let diff;

        if (game.team1_id === id) {
          diff = game.result1 - game.result2;
        } else {
          diff = game.result2 - game.result1;
        }

        stats.diff += diff;

        if (diff > 0) {
          stats.points += 3;
        } else if (diff === 0) {
          stats.points += 1;
        }
      });

      return stats;
    },

    tableByTeamIds: (state, getter) => (teamIds) => {
      const teamStats = teamIds.map(id => {
        return {
          team: getter.teamById(id),
          stats: getter.teamStats(id)
        };
      });

      return [...teamStats].sort((a, b) => {
        if (a.stats.points < b.stats.points) {
          return 1;
        }

        if (a.stats.points > b.stats.points) {
          return -1;
        }

        if (a.stats.diff < b.stats.diff) {
          return 1;
        }

        if (a.stats.diff > b.stats.diff) {
          return -1;
        }

        if (a.stats.games < b.stats.games) {
          return 1;
        }

        if (a.stats.games > b.stats.games) {
          return -1;
        }

        return 0;
      });
    },

    gamesByTeam: (state, getters) => (teamId) => {
      return [...getters.games].filter(game => {
        return game.team1_id === teamId || game.team2_id === teamId;
      });
    },

    gamesByGroup: (state, getters) => (groupId) => {
      return [...getters.games].filter(game => {
        if (!game.is_preliminary) {
          return false;
        }

        const team = getters.teamById(game.team1_id);
        return team.group_id === parseInt(groupId);
      });
    },

    gamesByType: (state, getters) => (type) => {
      const games = [...getters.games];

      if (!type) {
        return games;
      }

      if (type === 'last-sixteen') {
        return games.filter(game => {
          return game.is_last_sixteen;
        });
      }

      if (type === 'quarter-final') {
        return games.filter(game => {
          return game.is_quarter_final;
        });
      }

      if (type === 'semi-final') {
        return games.filter(game => {
          return game.is_semi_final;
        });
      }

      if (type === '3rd-place') {
        return games.filter(game => {
          return game.is_game_for_3rd_place;
        });
      }

      if (type === 'final') {
        return games.filter(game => {
          return game.is_final;
        });
      }

      const team = getters.teamById(type);
      if (team) {
        return getters.gamesByTeam(type);
      }

      return null;
    },

    teamGamesBefore: (state, getters) => (teamId, playingTimestamp) => {
      return [...getters.gamesByTeam(teamId)].filter(game => {
        return game.playing_timestamp < playingTimestamp;
      });
    },

    gameDay: (state, getters) => (game) => {
      if (game.is_preliminary) {
        const pastTeamGames = getters.teamGamesBefore(game.team1_id, game.playing_timestamp);

        return pastTeamGames.length + 1 + '. Game Day';
      }

      if (game.is_last_sixteen) {
        return 'Last Sixteen';
      }

      if (game.is_quarter_final) {
        return 'Quarter Final';
      }

      if (game.is_semi_final) {
        return 'Semi Final';
      }

      if (game.is_game_for_3rd_place) {
        return 'Game for 3rd Place';
      }

      if (game.is_final) {
        return 'Final';
      }
    },

    imprint(state) {
      return state.imprint;
    }
  }

});

export default store;
