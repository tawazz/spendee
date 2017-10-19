<template >
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <item-list :data="incomes" :color="color" :type="type" :editCallback="editInc" />
      </div>
      <div class="col-md-6">
        <graphs :data="incomes" :tagChart="tagData" :color="color" :type="type" />
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
  import {axios,apis,utils,store} from '@/hooks'
  import Vue from 'vue'

  export default {
    name:'incomes',
    data:function () {
      return {
        type:"income",
        color:"green",
        showAddModal:false,
        selected_inc: null
      };
    },
    components:{
      'item-list':itemsList,
      'graphs':graphs,
      'add-inc': addIncomeModal
    },
    beforeRouteEnter (to, from, next) {
      store.dispatch('loading');
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
          vm.$store.dispatch('done');
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
            "incomes": state => state.incomes,
            tagData: state => state.tagData
        })
    },
    watch:{
        $route:function () {
          this.updatePage();
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

        vm.$store.dispatch('loading');
        axios.get(apis.incomes(year,month)).then(response => {
            vm.$store.dispatch('updateInc',response.data);
            vm.$store.dispatch('done');
        }).catch((error)=>{
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
        vm.$store.dispatch('loading');
        axios.get(apis.totals(year,month,null)).then(res => {
          vm.$store.dispatch('setTotals',res.data);
          vm.$store.dispatch('done');
        }).catch((error)=>{
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
      },
      editInc(id,type) {
        let vm = this;
        vm.$store.dispatch('loading');
        axios.get(apis.income(id)).then((response)=>{
          vm.$store.dispatch('done');
          this.showAddModal = true;
          vm.selected_inc = response.data;
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
