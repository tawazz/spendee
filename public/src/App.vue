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
  data:function () {
    return{
      expdata:[]
    }
  },
  components:{
    Dash,
    Navbar,
    FooterArea
  },
  mounted:function () {
    var vm =this;
  }
}
</script>

<style>
</style>
