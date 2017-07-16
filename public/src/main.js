// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import bs from 'bootstrap'
import store from '@/vuex/store'
import axios from 'axios'


require('../../css/dist/spendee.css');
require('../static/card.css');
require('../static/mdi.css');
require('../node_modules/mdi/css/materialdesignicons.css')

Vue.config.productionTip = false;
Vue.prototype.$http = axios;

new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: { App }
})
