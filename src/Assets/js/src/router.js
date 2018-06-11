import Vue from 'vue';
import VueRouter from 'vue-router';

import store from './store';

import Games from './views/Games.vue';
import GroupTable from './views/GroupTable.vue';
import Imprint from './views/Imprint.vue';
import Login from './views/Login.vue';
import LostPassword from './views/LostPassword.vue';
import NotFound from './views/NotFound.vue';
import Overview from './views/Overview.vue';
import Ranking from './views/Ranking.vue';
import ResetPassword from './views/ResetPassword.vue';
import Signup from './views/Signup.vue';
import Table from './views/Table.vue';
import VerifyEmail from './views/VerifyEmail.vue';

Vue.use(VueRouter);

const baseUrl = global.window.AppConfig.appBaseUrl;

const routes = [
  {
    path: '/',
    name: 'overview',
    components: {
      default: Overview
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/games',
    name: 'games',
    components: {
      default: Games
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/group/:groupId/table',
    name: 'group-table',
    components: {
      default: GroupTable
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/table',
    name: 'table',
    components: {
      default: Table
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/ranking',
    name: 'ranking',
    components: {
      default: Ranking
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/imprint',
    name: 'imprint',
    components: {
      default: Imprint
    }
  },
  {
    path: '/login',
    name: 'login',
    components: {
      default: Login
    }
  },
  {
    path: '/logout',
    name: 'logout',
    beforeEnter: (to, from, next) => {
      store.dispatch('LOGOUT', store.getters.token).finally(() => {
        next('/login');
      });
    },
    meta: {
      auth: true
    }
  },
  {
    path: '/signup',
    name: 'signup',
    components: {
      default: Signup
    }
  },
  {
    path: '/lost-password',
    name: 'lost-password',
    components: {
      default: LostPassword
    }
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    components: {
      default: ResetPassword
    }
  },
  {
    path: '/verify/:token',
    name: 'verify-email',
    components: {
      default: VerifyEmail
    }
  },
  {
    path: '/request-verification',
    name: 'request-verification',
    components: {
      default: VerifyEmail
    },
    meta: {
      auth: true
    }
  },
  {
    path: '*',
    component: NotFound
  }
];

const router = new VueRouter({
  routes,
  base: baseUrl,
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return new Promise(resolve => {
        router.app.$root.$once('triggerScroll', () => {
          resolve(savedPosition);
        });
      });
    }
  }
});

router.beforeEach((to, from, next) => {
  const authRequired = to.matched.some((route) => route.meta.auth);
  const token = store.getters.token;
  const tokenPresent = token !== null;
  const authenticated = store.getters.isAuthenticated;

  if (!authRequired) {
    next();
    return;
  }

  if (authenticated) {
    next();
    return;
  }

  if (tokenPresent) {
    store.dispatch('FETCH_USER_INFO', token)
      .then(() => {
        next();
      })
      .catch(() => {
        store.dispatch('RESET_USER').then(() => {
          store.dispatch('LOGIN_REDIRECT', { to, next });
        });
      });
  } else {
    store.dispatch('LOGIN_REDIRECT', { to, next });
  }
});

export default router;
