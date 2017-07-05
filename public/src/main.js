// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import App from './App';
import router from './router';
import bs from 'bootstrap';
require('../../css/dist/spendee.css');
require('../static/card.css');
require('../static/mdi.css');
require('../node_modules/mdi/css/materialdesignicons.css')

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
