<template>
  <div id="app">
    <Navbar/>
    <router-view/>
  </div>
</template>

<script>
import Navbar from './components/Navbar';
export default {
  name: 'App',
  components: {
    Navbar
  }
}
</script>

<style>
.navbar nav {
  padding: 0 20px;
}
</style>
