<template>
  <div>
  <Login></Login>
  </div>
</template>

<script>
import Login from './Login'

export default {
  components: {
    Login
  },
  created() {
    this.$store.dispatch('destroyToken')
    this.$store.dispatch('clearUserDetails')
  }
}
</script>
