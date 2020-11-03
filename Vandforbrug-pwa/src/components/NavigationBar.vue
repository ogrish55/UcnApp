<template>
  <div>
  <nav class="navbar navbar-expand-lg navbar-light">
    <router-link class="nav-link" to="/"><a class="navbar-brand">Water<img src="@/assets/icons8-water_filled.png" id="logo">Usage</a></router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link to="/" class="nav-link">Kontrolpanel</router-link>
        </li>
      </ul>
      <span>
        <ul class="nav">
          <li>
            <button type="button" class="btn btn-outline-primary rounded-pill btn-sm">Kontakt</button>
          </li>

          <li v-if="!loggedIn">
            <router-link :to="{ name: 'login' }">
            <button type="button" class="btn btn-outline-success rounded-pill btn-sm">
            Log ind</button>
            </router-link>
          </li>

          <li v-if="loggedIn">
            <router-link :to="{ name: 'logout' }">
            <button type="button" class="btn btn-outline-danger rounded-pill btn-sm">
            Log ud</button>
            </router-link>
          </li>
        </ul>
      </span>
    </div>
  </nav>
  </div>
</template>

<script>
export default {
  name: 'NavigationBar',
  computed: {
    loggedIn () {
      return this.$store.getters.loggedIn
    }
  }
}
</script>

<style>
#logo {
    width: 25px;
    height: 25px;
    margin-bottom: 5px;
  }
.nav {
  display: flex;
  list-style: none;
  align-items: stretch;
  justify-content: space-between;
}
.navbar {
  margin-bottom: 35px;
  -webkit-box-shadow: 0 8px 6px -6px #999;
  -moz-box-shadow: 0 8px 6px -6px #999;
  box-shadow: 0 8px 6px -6px #999;
  height: 60px;
}
.navbar-brand {
  color: #0269A4;
}
</style>
