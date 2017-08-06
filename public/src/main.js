// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import bs from 'bootstrap'
import store from '@/vuex/store'
import axios from 'axios'
import $ from 'jquery'
import material from 'material'
require('./hooks-css')

Vue.config.productionTip = false;
Vue.prototype.$http = axios;
$.material.init();
new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: { App }
})
