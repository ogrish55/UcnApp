import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://backend.test/api'

export const store = new Vuex.Store({
  state: {
    token: localStorage.getItem('access_token') || null,
    user: {
      firstName: '',
      lastName: '',
      address: '',
      email: '',
      phoneNumber: '',
      aconto: ''
    }
  },
  getters: {
    loggedIn (state) {
      return state.token !== null
    },
    getUserDetails (state) {
      return state.user
    }
  },
  mutations: {
    storeToken (state, token) {
      state.token = token
    },
    destroyToken (state) {
      state.token = null
    },
    storeUser (state, user) {
      state.user.firstName = user.firstName
      state.user.lastName = user.lastName
      state.user.address = user.address
      state.user.email = user.email
      state.user.phoneNumber = user.phoneNumber
      state.user.aconto = user.aconto
    },
    clearUserDetails (state) {
      state.user.firstName = ''
      state.user.lastName = ''
      state.user.phoneNumber = ''
      state.user.email = ''
      state.user.address = ''
      state.user.aconto = ''
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
      } catch (error) {
        throw error
      }
    },
    retrieveUser(context) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
      axios.get('/user')
        .then(response => {
          context.commit('storeUser', response.data)
        })
        .catch(error => {
          console.log(error)
        })
    },
    updateUser(context, user) {
      axios.patch('/updateUser', {
        firstName: user.firstName,
        lastName: user.lastName,
        email: user.email,
        phoneNumber: user.phoneNumber
      })
        .then(response => {
          context.commit('storeUser', response.data)
        })
        .catch(error => {
          console.log(error)
        })
    },
    clearUserDetails(context){
      context.commit('clearUserDetails')
    }
  }
})
