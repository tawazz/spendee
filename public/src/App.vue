<template>
  <div>
    <Navbar />
    <div class="container-fluid">
      <Dash />
      <router-view />
    </div>
    <FooterArea />
  </div>
</template>

<script>
import Dash from './components/dash'
import Navbar from './components/nav'
import FooterArea from './components/footer'
export default {
  name: 'app',
  components:{
    Dash,
    Navbar,
    FooterArea
  }
}
</script>

<style>
</style>
