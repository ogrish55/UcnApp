<template>
  <footer class="sticky-footer bg-white">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Copyright &copy; UCN gruppe 1 2020 Let's Gooo</span>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterComponent"
}
</script>

<style scoped>

.sticky-footer {
  /* gradient baggrund */
  position: fixed;
  bottom: 0;
  width: 100%;
  background: rgb(2, 0, 36);
  background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(73, 87, 255, 0.4150035014005602) 0%, rgba(255, 0, 0, 0.2553396358543417) 100%);
}
</style>
