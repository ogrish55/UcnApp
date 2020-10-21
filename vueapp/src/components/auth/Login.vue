<template>
  <div>
    <div class="col-md-4 offset-md-4">
      <img src="@/assets/icons8-water_filled.png" id="logo">
      <p>Log ind!</p>
    </div>
    <div class="shadow p-3 mb-5 bg-white rounded col-md-4 offset-md-4">
      <form action="#" @submit.prevent="login">
        <div class="form-group">
          <div id="label" class="offset-sm-2"><label id="label">E-mail</label></div>
          <input type="email" class="form-control col-8 offset-md-2" id="inputEmail" aria-describedby="emailHelp"
                 placeholder="Indtast email"
                 v-model="username">
        </div>
        <div class="form-group">
          <div id="label"><label id="label" class="offset-sm-2">Adgangskode</label></div>
          <input type="password" class="form-control col-8 offset-md-2" id="inputPassword"
                 placeholder="Indtast adgangskode" v-model="password">
        </div>
        <button type="submit" class="btn btn-success col-4">Log ind</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    login () {
      try {
        this.$store.dispatch('retrieveToken', {
          username: this.username,
          password: this.password
        }).then(response => {
          this.$router.push({name: 'user'})
        })
      } catch (error) {
        console.log(error)
      }
    }
  }
}
</script>

<style scoped>
#logo {
  width: 80px;
  height: 80px;
  margin-bottom: 5px;
}

#label {
  text-align: left;
}

</style>
