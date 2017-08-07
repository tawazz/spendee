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
        <a href="#" class="btn btn-danger btn-fab" @click.prevent="addButtonClick" ><i class="material-icons mdi mdi-plus"></i><div class="ripple-container"></div></a>
      </div>
    </div>
    <add-exp :show="showAddModal" :save="addExpense" :close="closeModal"/>
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
        showAddModal:false
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs,
      'add-exp':addExpenseModal
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
                vm.$store.dispatch('updatePage',"expenses");
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
    methods:{
      addButtonClick () {
        let vm =this;
        vm.showAddModal = true;
      },
      addExpense() {
        let vm = this;
        vm.closeModal();
      },
      closeModal() {
          this.showAddModal = false;
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
    padding: 50px;
  }
</style>
