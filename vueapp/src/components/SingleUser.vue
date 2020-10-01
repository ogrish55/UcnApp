<template>
  <div>
    <h1>User:</h1>
    <div v-if="info">
      <p>Name: {{ info.name }}</p>
      <p>Email: {{ info.email }}</p>
      <p>Phone: {{ info.phone }}</p>
    </div>
    <button style="width: 150px" type="button" class="btn-primary btn" @click="goBack()"> Back</button>
  </div>
</template>

<script>
import axios from 'axios'
import HelloWorld from './HelloWorld'

export default {
  name: 'SingleUser',
  components: {HelloWorld},
  data () {
    return {
      info: null
    }
  },
  mounted () {
    axios
      .get('http://localhost:8000/api/users/' + this.$route.params.userId)
      .then(response => (this.info = response.data))
  },
  methods: {
    goBack () {
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>

</style>
