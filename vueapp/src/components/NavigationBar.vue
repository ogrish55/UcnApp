<template>
  <div>
  <nav class="navbar navbar-expand-lg navbar-light">
    <router-link class="nav-link" to="/"><a class="navbar-brand">Water<img src="@/assets/icons8-water_filled.png"
                                                                           id="logo">Usage</a></router-link>
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
      <button type="button" class="btn btn-outline-success rounded-pill btn-sm">Log ind</button>
      <button type="button" class="btn btn-outline-primary rounded-pill btn-sm">Kontakt</button>
      </span>
    </div>
  </nav>
  </div>
</template>

<script>
export default {
  name: 'NavigationBar'
}
</script>

<style scoped>

#logo {
  width: 25px;
  height: 25px;
  margin-bottom: 5px;
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
