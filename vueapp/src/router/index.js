import Vue from 'vue'
import Router from 'vue-router'
import FrontPage from '../components/FrontPage'
import Users from '@/components/Users'
import SingleUser from '../components/SingleUser'
import Pricing from '../components/Pricing'
import Login from '../components/Login'
import MonthlyMeasurements from '../components/MonthlyMeasurements'
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'FrontPage',
      component: FrontPage
    },
    {
      path: '/user/:userId',
      name: 'user',
      component: SingleUser,
      props: true
    },
    {
      path: '/users/',
      component: Users
    },
    {
      path: '/login/',
      component: Login
    },
    {
      path: '/pricing',
      component: Pricing
    },
    {
      path: '/user/:userId/monthly',
      name: 'monthly',
      component: MonthlyMeasurements
    }
  ],
  mode: 'history'
})
