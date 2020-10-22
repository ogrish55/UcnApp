<template>
  <div>

  </div>
</template>

<script>
export default {
  created () {
    this.$store.dispatch('destroyToken')
    this.$store.dispatch('clearUserDetails')
      .then(response => {
        this.$router.push({name: 'FrontPage'})
      })
  }
}
</script>
