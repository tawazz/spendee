<template >
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <item-list :data="expenses" :color="color" :type="type" :editCallback="editExp"/>
      </div>
      <div class="col-md-6">
        <exp-graphs :expenses="expenses" :tagChart="tagData" :type="type" :color="color" />
      </div>
      <div class="btn-add">
        <a href="#" class="btn btn-danger btn-fab" @click.prevent="addButtonClick" ><i class="material-icons mdi mdi-plus"></i><div class="ripple-container"></div></a>
      </div>
    </div>
    <add-exp :show="showAddModal" :save="addExpense" :close="closeModal" :selected_exp="selected_exp"/>
  </div>
</template>
<script>
  import itemsList from '@/components/item-list'
  import graphs from '@/components/graphs'
  import addExpenseModal from '@/components/expenses/add-expense'
  import { mapState } from 'vuex'
  import {axios,apis} from '@/hooks'
  import Vue from 'vue'

  export default {
    name:'expenses',
    data:function () {
      return {
        color:"red",
        type:"expense",
        showAddModal:false,
        selected_exp:null
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs,
      'add-exp':addExpenseModal
    },
    computed:{
        ...mapState({
            expenses: state => state.expenses,
            tagData: state => state.tagData,
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
                vm.$store.dispatch('loading');
                axios.get(apis.tagData(year,month)).then((response)=>{
                  vm.$store.dispatch('updateTagData',response.data);
                  vm.$store.dispatch('done');
                });
                vm.$store.dispatch('updateExp',response.data);
                vm.$store.dispatch('updatePage',"expenses");
            });
        });
    },
    watch:{
        $route:function () {
            this.updatePage();
        }
    },
    methods:{
      addButtonClick () {
        let vm =this;
        vm.showAddModal = true;
      },
      addExpense() {
        let vm = this;
        vm.updatePage();
        vm.closeModal();

      },
      updatePage(){
        let vm = this;
        let params = vm.$route.params;
        let year = params.year;
        let month = params.month;
        vm.$store.dispatch('loading');
        axios.get(apis.expenses(year,month)).then(response => {
            vm.$store.dispatch('updateExp',response.data);
            vm.$store.dispatch('done');
        });
        vm.$store.dispatch('loading');
        axios.get(apis.tagData(year,month)).then((response)=>{
          vm.$store.dispatch('updateTagData',response.data);
          vm.$store.dispatch('done');
        });
        vm.$store.dispatch('loading');
        axios.get(apis.totals(year,month,null)).then(res => {
          vm.$store.dispatch('setTotals',res.data);
          vm.$store.dispatch('done');
        });
      },
      closeModal() {
          this.showAddModal = false;
      },
      init(){
        let vm = this;
      },
      editExp(id,type) {
        let vm = this;
        axios.get(apis.expense(id)).then((response)=>{
          this.showAddModal = true;
          vm.selected_exp = response.data;
        });
      }
    },
    mounted:function () {
      let vm =this;
      vm.init();
    }
  }
</script>
<style >
  .btn-add{
    position: fixed;
    bottom: 0px;
    right: 0px;
    padding: 50px;
  }
</style>
