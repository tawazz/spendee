import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router({
  'mode': 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/containers/expenses')
    },{
      path: '/expenses/:year?/:month?',
      name: 'Expenses',
      component: () => import('@/containers/expenses')
    },{
      path: '/incomes/:year?/:month?',
      name: 'Incomes',
      component: () => import('@/containers/incomes')
    }
  ]
})
