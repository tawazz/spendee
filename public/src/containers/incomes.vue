<template >
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <item-list :data="incomes" :color="color" :type="type" />
      </div>
      <div class="col-md-6">
        <exp-graphs :areaChart="incomes" :color="color" :type="type" />
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
    name:'incomes',
    data:function () {
      return {
        expdata:[],
        type:"income",
        color:"green"
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs
    },
    beforeRouteEnter (to, from, next) {
        let year = (to.params.year)?to.params.year:null;
        let month = (to.params.month?to.params.month:null);
        axios.get(apis.incomes(year,month)).then(response => {
            next(vm => {
                vm.$store.dispatch('updateNav',{
                    year,
                    month,
                    day:null
                });
                vm.$store.dispatch('updateInc',response.data);
                vm.$store.dispatch('updatePage',"incomes");
            });
        });
    },
    computed:{
        ...mapState({
            "incomes": state => state.incomes
        })
    },
    watch:{
        $route:function () {
            let vm = this;
            let params = vm.$route.params;
            let year = params.year;
            let month = params.month;
            axios.get(apis.incomes(year,month)).then(response => {
                vm.$store.dispatch('updateInc',response.data);
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
