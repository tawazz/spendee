import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import Expenses from '@/containers/expenses'
import Incomes from '@/containers/incomes'

Vue.use(Router)

export default new Router({
  'mode': 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Expenses
    },{
      path: '/expenses',
      name: 'Expenses',
      component: Expenses
    },{
      path: '/incomes',
      name: 'Incomes',
      component: Incomes
    }
  ]
})
