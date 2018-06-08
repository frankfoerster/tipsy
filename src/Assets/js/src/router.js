import Vue from 'vue';
import VueRouter from 'vue-router';

import store from './store';

import GamePlan from './views/GamePlan.vue';
import Login from './views/Login.vue';
import LostPassword from './views/LostPassword.vue';
import NotFound from './views/NotFound.vue';
import Ranking from './views/Ranking.vue';
import Signup from './views/Signup.vue';
import ResetPassword from './views/ResetPassword.vue';

Vue.use(VueRouter);

const baseUrl = global.window.AppConfig.appBaseUrl;

const requireAuth = (to, from, next) => {
  if (!store.getters.isAuthenticated) {
    window.location.href = baseUrl + '/login';
  } else {
    next();
  }
};

const routes = [
  {
    path: '/',
    name: 'game-plan',
    components: {
      default: GamePlan
    },
    beforEnter: requireAuth
  },
  {
    path: '/',
    name: 'ranking',
    components: {
      default: Ranking
    },
    beforEnter: requireAuth
  },
  {
    path: '/login',
    name: 'login',
    components: {
      default: Login
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
    path: '*',
    component: NotFound
  }
];

const router = new VueRouter({
  routes,
  base: baseUrl,
  mode: 'history'
});

export default router;
