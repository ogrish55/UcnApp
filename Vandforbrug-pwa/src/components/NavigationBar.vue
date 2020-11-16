<template>
  <div>
  <nav class="navbar navbar-expand-lg navbar-light">
    <router-link class="nav-link" to="/"><a class="navbar-brand ">Water<img src="@/assets/icons8-water_filled.png" id="logo">Usage</a></router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link to="/" class="nav-link font-weight-bold">Kontrolpanel</router-link>
        </li>
      </ul>
      <span>
        <ul class="nav">
          <li>
            <button type="button" class="btn btn-primary rounded-pill btn-m">Kontakt</button>
          </li>

          <li v-if="!loggedIn">
            <router-link :to="{ name: 'login' }">
            <button type="button" class="btn btn-success rounded-pill btn-m">
            Log ind</button>
            </router-link>
          </li>

          <li v-if="loggedIn">
            <router-link :to="{ name: 'logout' }">
            <button type="button" class="btn btn-danger rounded-pill btn-m">
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
    width: 35px;
    height: 35px;
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
  /* gradient baggrund */
  background: rgb(34,193,195);
  background: linear-gradient(90deg, rgba(34,193,195,0.3841911764705882) 0%, rgba(253,187,45,0.3253676470588235) 100%);
}
.navbar-brand {
  color: #0269A4;
}
</style>
