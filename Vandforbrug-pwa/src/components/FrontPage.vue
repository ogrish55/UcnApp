<template>
  <div>
    <Login v-if="someBoolean ===! true"></Login>
    <!--  v-if="someBoolean === false"-->
    <dashboard v-if="someBoolean"></dashboard>
  </div>
</template>

<script>
import Login from './auth/Login'
import {store} from '@/store/store'
import Dashboard from './Dashboard'

export default {
  name: 'FrontPage',
  components: {
    Login,
    store,
    Dashboard
  },
  data() {
    return {
      msg: 'FRONTPAGE',
      test: '',
      number: null,
      someBoolean: false,
    }
  },
  methods: {
    submit() {
      if (this.number != null) {
        console.log(this.number)
        const userId = this.number
        this.$router.push({name: 'user', params: {userId}})
      } else {
        alert('Hey! Enter a number')
      }
    }
  },
  created() {
    this.someBoolean = store.getters.loggedIn
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
