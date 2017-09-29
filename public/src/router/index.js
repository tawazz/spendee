import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router({
  'mode': 'history',
  routes: [
    {
      path: '/',
      name: 'default',
      component: () => import('@/layouts/default'),
      children: [
        {
          path: 'login',
          component: () => import('@/layouts/default'),
          children:[
            {
              path:'',
              name: 'login',
              component: () => import('@/containers/login')
            }
          ]

        },
        {
          path: 'expenses/:year?/:month?',
          component: () => import('@/layouts/main'),
          children:[
            {
              path: '',
              name:'Expenses',
              component: () => import('@/containers/expenses')
            }
          ]
        },{
          path: 'incomes/:year?/:month?',
          component: () => import('@/layouts/main'),
          children:[
            {
              path:'',
              name:'Incomes',
              component: () => import('@/containers/incomes')
            }
          ]
        },{
          path: 'tags/:id?/:year?/:month?',
          component: () => import('@/layouts/main'),
          children: [
            {
              path: '',
              name: 'Tags',
              component: () => import('@/containers/tags')
            }
          ]

        },{
          path: 'overview/:year?',
          component: () => import('@/layouts/main'),
          children: [
            {
              path: '',
              name: 'Overview',
              component: () => import('@/containers/overview')
            }
          ]

        }
      ]
    }

  ]
})
