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

axios.interceptors.request.use(function (config) {
  if (config.method != 'get') {
    config.data = {
      ...config.data,
      "csrf_name" : Window.csrf.name,
      "csrf_value": Window.csrf.value
    };
  }
    return config;
  }, function (error) {
    if (error.hasOwnProperty("response")) {
      if (error.response.status == 401) {
        document.location.assign('/login');
      }
    } else {
      console.error(error);
    }
    return Promise.reject(error);
  });

Vue.config.productionTip = false;
Vue.prototype.$http = axios;
$.material.init();
const app = new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: { App }
});
