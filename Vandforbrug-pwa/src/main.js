import Vue from 'vue'
import App from './App.vue'
// import router from './router'
import routes from './router/routes'
// import store from './store'
import 'bootstrap'
import {store} from './store/store'
import VueRouter from 'vue-router'
import './registerServiceWorker'


Vue.config.productionTip = false
Vue.use(VueRouter)

const router = new VueRouter({
  routes,
  mode: 'history'
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store.getters.loggedIn) {
      next({
        name: 'login'
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.requiresVisitor)) {
    if (store.getters.loggedIn) {
      next({
        name: 'user'
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

// new Vue({
//   el: '#app',
//   router: router,
//   store: store,
//   components: {App},
//   template: '<App/>'
// })

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount('#app')
