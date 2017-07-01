<template>
  <div>
    <Nav />
    <div class="container">
      <Dash />
      <router-view />
    </div>
    <Footer />
  </div>
</template>

<script>
import Dash from './components/dash'
import Nav from './components/nav'
import Footer from './components/footer'
export default {
  name: 'app',
  components:{
    Dash,
    Nav,
    Footer
  }
}
</script>

<style>
</style>
