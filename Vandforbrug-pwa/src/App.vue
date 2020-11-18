<template>
  <div id="app">
    <navigation-bar></navigation-bar>
    <!-- <h1>{{ test }}</h1> -->
    <!-- <input type="text" placeholder="Enter your title" v-model="test"> -->
    <router-view></router-view>

    <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; UCN gruppe 1 2020 Let's Gooo</span>
          </div>
        </div>
      </footer>
    <!-- End of Footer -->
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
.sticky-footer {
  /* gradient baggrund */
  background: rgb(2, 0, 36);
  background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(73, 87, 255, 0.4150035014005602) 0%, rgba(255, 0, 0, 0.2553396358543417) 100%);
  position: fixed;
  bottom: 0;
  width: 100%;
}
</style>
