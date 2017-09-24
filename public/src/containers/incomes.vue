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
        <a href="#" class="btn btn-success btn-fab" @click.prevent="showAddIncomeModal"><i class="material-icons mdi mdi-plus"></i><div class="ripple-container"></div></a>
      </div>
    </div>
    <add-inc :show="showAddModal" :save="addIncome" :close="closeModal" :selected_inc="selected_inc"/>
  </div>
</template>
<script>
  import itemsList from '@/components/item-list'
  import addIncomeModal from '@/components/incomes/add'
  import graphs from '@/components/graphs'
  import { mapState } from 'vuex'
  import {axios,apis,utils} from '@/hooks'
  import Vue from 'vue'

  export default {
    name:'incomes',
    data:function () {
      return {
        expdata:[],
        type:"income",
        color:"green",
        showAddModal:false,
        selected_inc: null
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs,
      'add-inc': addIncomeModal
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
        }).catch((error)=>{
          next(vm => {
            utils.error_handler(vm,error);
            vm.$store.dispatch('done');
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
            }).catch((error)=>{
              utils.error_handler(vm,error);
              vm.$store.dispatch('done');
            });
        }
    },
    methods: {
      addIncome() {
        this.closeModal();
        this.updatePage();
      },
      closeModal() {
        this.showAddModal = false;
      },
      showAddIncomeModal(){
        this.showAddModal = true;
      },
      updatePage(){
        let vm = this;
        let params = vm.$route.params;
        let year = params.year;
        let month = params.month;

        axios.get(apis.incomes(year,month)).then(response => {
            vm.$store.dispatch('updateInc',response.data);
        }).catch((error)=>{
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
      }
    },
    mounted:function () {
      let vm =this;
    }
  }
</script>
<style >
  .btn-add{
    position: fixed;
    bottom: 0px;
    right: 0px;
    margin-right: 50px;
    margin-bottom: 50px;
    z-index: 4;
  }
</style>
