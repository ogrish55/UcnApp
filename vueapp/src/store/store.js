import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://backend.test/api'

export const store = new Vuex.Store({
  plugins: [createPersistedState({
    storage: window.sessionStorage
  })],
  state: {
    token: localStorage.getItem('access_token') || null,
    user: null
  },
  getters: {
    loggedIn (state) {
      return state.token !== null
    },
    getUser (state) {
      return state.user
    }
  },
  mutations: {
    storeToken (state, token) {
      state.token = token
    },
    destroyToken (state) {
      state.token = null
      state.user = null
    },
    storeUser (state, user) {
      state.user = user
    }
  },
  actions: {
    register (context, data) {
      return new Promise((resolve, reject) => {
        axios
          .post('/register', {
            name: data.name,
            email: data.email,
            password: data.password
          })
          .then(response => {
            resolve(response)
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    destroyToken (context) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token

      if (context.getters.loggedIn) {
        return new Promise((resolve, reject) => {
          axios.post('/logout')
            .then(response => {
              localStorage.removeItem('access_token')
              context.commit('destroyToken')
              resolve(response)
            })
            .catch(error => {
              localStorage.removeItem('access_token')
              context.commit('destroyToken')
              reject(error)
            })
        })
      }
    },
    async retrieveToken (context, credentials) {
      try {
        const response = await axios.post('/login', {
          username: credentials.username,
          password: credentials.password
        })
        const token = response.data.access_token
        localStorage.setItem('access_token', token)
        context.commit('storeToken', token)
        await context.dispatch('getUser')
      } catch (error) {
        console.log(error)
      }
    },
    async getUser (context) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
      if (context.getters.loggedIn) {
        try {
          const response = await axios.get('/user')
          const user = response.data
          context.commit('storeUser', user)
        } catch (error) {
          console.log(error)
        }
      }
    }
  }
})
