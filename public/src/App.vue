<template>
  <div>
    <Navbar />
    <div class="container-fluid">
      <Dash :bal="expdata.balance" :exp="expdata.exp_total" :inc="expdata.inc_total"/>
      <router-view />
    </div>
    <FooterArea />
  </div>
</template>

<script>
import Dash from './components/dash'
import Navbar from './components/nav'
import FooterArea from './components/footer'
const expdata =  require('../static/data.json');
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
    let vm =this;
    vm.expdata = expdata;
  }
}
</script>

<style>
</style>
