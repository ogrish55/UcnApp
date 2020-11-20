<template>
  <div>
  <nav class="navbar navbar-expand-lg navbar-light">
    <router-link class="nav-link" to="/dashboard"><a class="navbar-brand ">Water<img src="@/assets/icons8-water_filled.png" id="logo">Usage</a></router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        
      </ul>
      <span>
        <ul class="navbar-nav mr-auto">
          
          <li v-if="!loggedIn" class="nav-item">
            <router-link :to="{ name: 'login' }" class="nav-link font-weight-bold">Log ind</router-link>
          <li v-if="loggedIn" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle font-weight-bold" data-toggle="dropdown" data-target="dropdown_target">
              Hej {{ this.$store.state.user.firstName }}
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown_target">
              <router-link :to="{ name: 'details' }" class="dropdown-item">Profil</router-link>
              <router-link :to="{ name: 'dashboard' }" class="dropdown-item">Mit forbrug</router-link>
              <router-link :to="{ name: 'SavingTips' }" class="dropdown-item">Sparetips</router-link>
              <a class="dropdown-item disabled" aria-disabled="true">Kontakt</a>
              <div class="dropdown-divider"></div>
              <router-link :to="{ name: 'logout' }" class="dropdown-item">Log ud</router-link>
            </div>
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
  margin-bottom: 5px;
  -webkit-box-shadow: 0 8px 6px -6px #999;
  -moz-box-shadow: 0 8px 6px -6px #999;
  box-shadow: 0 8px 6px -6px #999;
  height: 60px;
  /* gradient baggrund */
  background: rgb(34,193,195);
  background: linear-gradient(90deg, rgba(253,187,45,0.3253676470588235) 0%, rgba(34,193,195,0.3841911764705882) 100%);
}
.navbar-brand {
  color: #0269A4;
}
</style>
