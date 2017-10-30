<template>
  <div>
    <Navbar />
    <div class="container-fluid">
      <router-view />
    </div>
    <FooterArea />
    <loading  :isLoading="busy" ref="Loading"></loading>
  </div>
</template>

<script>
import Dash from '@/components/dash'
import Navbar from '@/components/nav'
import FooterArea from '@/components/footer'
import Loading from '@/components/helpers/loader';
import { mapState } from 'vuex'
export default {
  name: 'app',
  data:function () {
    return{
    }
  },
  components:{
    Dash,
    Navbar,
    FooterArea,
    'loading':Loading
  },
  computed:{
      ...mapState({
          busy: state => state.busy > 0
      })
  },
  mounted:function () {
    var vm =this;
  }
}
</script>

<style>
</style>
