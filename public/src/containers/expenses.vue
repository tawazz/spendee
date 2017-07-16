<template >
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <item-list :data="expenses" :color="color" :type="type"/>
      </div>
      <div class="col-md-6">
        <exp-graphs :data="expenses" :type="type" :color="color" />
      </div>
      <div class="btn-add">
        <a href="javascript:void(0)" class="btn btn-danger btn-fab"><i class="material-icons mdi mdi-plus"></i><div class="ripple-container"></div></a>
      </div>
    </div>
  </div>
</template>
<script>
  import itemsList from '@/components/item-list'
  import graphs from '@/components/graphs'
  import { mapState } from 'vuex'
  import {axios,apis} from '@/hooks'
  import Vue from 'vue'

  export default {
    name:'expenses',
    data:function () {
      return {
        color:"red",
        type:"expense",
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs
    },
    computed:{
        ...mapState({
            "expenses": state => state.expenses
        })
    },
    beforeRouteEnter (to, from, next) {
        let year = to.params.year;
        let month = to.params.month;

        axios.get(apis.expenses(year,month)).then(response => {
            next(vm => {
                vm.$store.dispatch('updateNav',{
                    year,
                    month,
                    day:null
                });
                vm.$store.dispatch('updateExp',response.data);
            });
        });
    },
    watch:{
        $route:function () {
            let vm = this;
            let params = vm.$route.params;
            let year = params.year;
            let month = params.month;
            axios.get(apis.expenses(year,month)).then(response => {
                vm.$store.dispatch('updateExp',response.data);
            });
        }
    },
    mounted:function () {
      let vm =this;
    }
  }
</script>
<style media="screen">
  .btn.btn-fab i.material-icons, .input-group-btn .btn.btn-fab i.material-icons {
    line-height: 16px;
  }
  .btn-add{
    position: fixed;
    bottom: 0px;
    right: 0px;
    padding: 50px;
  }
</style>
