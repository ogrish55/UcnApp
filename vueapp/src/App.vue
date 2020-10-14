<template>
  <div id="app">
    <navigation-bar></navigation-bar>
    <!-- <h1>{{ test }}</h1> -->
    <!-- <input type="text" placeholder="Enter your title" v-model="test"> -->
    <router-view></router-view>
  </div>
</template>

<script>
import NavigationBar from './components/NavigationBar'

export default {
  name: 'App',
  components: {NavigationBar},
  data () {
    return {
      info: null
    }
  },
  methods: {
    goHome () {
      this.$router.push('/')
    }
  }

}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  background-color:  #FFFFFF;
  height: 100vh;
  overflow: auto;
}

</style>
