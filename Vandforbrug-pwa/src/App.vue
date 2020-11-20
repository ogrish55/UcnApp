<template>
  <div id="app">
    <navigation-bar></navigation-bar>
    <router-view></router-view>
    <footer-component></footer-component>
  </div>
</template>

<script>
import NavigationBar from './components/NavigationBar'
import FooterComponent from "@/components/FooterComponent";

export default {
  name: 'App',
  components: {NavigationBar, FooterComponent},
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
