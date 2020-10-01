import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Users from '@/components/Users'
import SingleUser from '../components/SingleUser'
import Pricing from '../components/Pricing'
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
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
      path: '/pricing',
      component: Pricing
    }
  ],
  mode: 'history'
})
