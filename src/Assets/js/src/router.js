import Vue from 'vue';
import VueRouter from 'vue-router';

import store from './store';

import ViewGames from './views/Games.vue';
import ViewImprint from './views/Imprint.vue';
import ViewLogin from './views/Login.vue';
import ViewLostPassword from './views/LostPassword.vue';
import ViewLostPasswordRequested from './views/LostPasswordRequested.vue';
import ViewNotFound from './views/NotFound.vue';
import ViewOverview from './views/Overview.vue';
import ViewRanking from './views/Ranking.vue';
import ViewResetPassword from './views/ResetPassword.vue';
import ViewResetPasswordComplete from './views/ResetPasswordComplete.vue';
import ViewSignup from './views/Signup.vue';
import ViewSignedUp from './views/SignedUp.vue';
import ViewTable from './views/Table.vue';
import ViewVerifyEmail from './views/VerifyEmail.vue';

import Games from './components/Games.vue';
import Tables from './components/Tables.vue';

Vue.use(VueRouter);

const baseUrl = global.window.AppConfig.appBaseUrl;

const routes = [
  {
    path: '/',
    name: 'overview',
    component: ViewOverview,
    meta: {
      auth: true
    }
  },
  {
    path: '/games',
    component: ViewGames,
    children: [
      {
        path: '',
        name: 'games',
        component: Games,
        meta: {
          auth: true
        }
      },
      {
        path: ':type',
        name: 'games-filtered',
        component: Games,
        meta: {
          auth: true
        }
      }
    ]
  },
  {
    path: '/table',
    component: ViewTable,
    children: [
      {
        path: '',
        name: 'table',
        component: Tables,
        meta: {
          auth: true
        }
      },
      {
        path: ':groupId',
        name: 'group-table',
        component: Tables,
        meta: {
          auth: true
        }
      }
    ]
  },
  {
    path: '/ranking',
    name: 'ranking',
    component: ViewRanking,
    meta: {
      auth: true
    }
  },
  {
    path: '/imprint',
    name: 'imprint',
    component: ViewImprint
  },
  {
    path: '/login',
    name: 'login',
    component: ViewLogin
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
    component: ViewSignup
  },
  {
    path: '/signed-up',
    name: 'signed-up',
    component: ViewSignedUp
  },
  {
    path: '/lost-password',
    name: 'lost-password',
    component: ViewLostPassword
  },
  {
    path: '/lost-password-requested',
    name: 'lost-password-requested',
    component: ViewLostPasswordRequested
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password',
    component: ViewResetPassword
  },
  {
    path: '/reset-password-complete',
    name: 'reset-password-complete',
    component: ViewResetPasswordComplete
  },
  {
    path: '/verify/:token',
    name: 'verify-email',
    component: ViewVerifyEmail
  },
  {
    path: '/request-verification',
    name: 'request-verification',
    component: ViewVerifyEmail,
    meta: {
      auth: true
    }
  },
  {
    path: '*',
    component: ViewNotFound
  }
];

const router = new VueRouter({
  routes,
  base: baseUrl,
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    let position = {x: 0, y: 0};

    if (savedPosition) {
      position = savedPosition;
    }

    return position;
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
