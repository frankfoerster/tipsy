import 'babel-polyfill';
import 'es6-promise/auto';

import Vue from 'vue';

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate);

import Vue2TouchEvents from 'vue2-touch-events'
Vue.use(Vue2TouchEvents);

import {sync} from 'vuex-router-sync';
import store from './store';
import router from './router';
import App from './components/App.vue';

sync(store, router);

const app = new Vue({
  store: store,
  router: router,
  render: h => h(App)
});

const appContainer = document.querySelector('#app');
if (appContainer !== null) {
    app.$mount('#app');
}
