<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    <router-link to="/users/">Users</router-link>
    <h1 class="myHeader">{{ test }}</h1>
    <div>Find for user with ID<input type="number" style="margin-left: 5px" v-model="number"></div>
    <button style="width: 150px; margin-top: 10px" @click="submit()" type="button" class="btn-primary btn">Find user</button>
  </div>
</template>

<script>
export default {
  name: 'FrontPage',
  data () {
    return {
      msg: 'FRONTPAGE',
      test: '',
      number: null
    }
  },
  methods: {
    submit () {
      if (this.number != null) {
        console.log(this.number)
        const userId = this.number
        this.$router.push({name: 'user', params: {userId}})
      } else {
        alert('Hey! Enter a number')
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}

.myHeader {
  color: aqua;
  font-weight: bold;
}

</style>
