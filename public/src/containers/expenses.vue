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
    <modal title="Add Expense" :isOpen="showAddModal" :ok="addExpense" :cancel="closeModal" okText="Save">
      <form name="addForm" id="addForm" method="post" action="/expenses/add">
          <div class="form-group">
              <input type="text" class="form-control" name="name" placeholder="Enter Item Name" style="cursor: auto;">
          </div>
          <div class="form-group">
              <input type="text" class="form-control money" name="cost"  placeholder="Enter Amount">
          </div>
          <div class="form-group">
              <input type="text" class="form-control datepicker" name="date" placeholder="Date" data-provide="datepicker" onfocus="blur();" onkeydown="return false">
          </div>
          <div class="form-group">
            <label for="tags">Tags</label>
            <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width:100%;height:50px;">
              <option value="tag.id">tag.name</option>
            </select>
          </div>
        </form>
    </modal>
  </div>
</template>
<script>
  import itemsList from '@/components/item-list'
  import graphs from '@/components/graphs'
  import modal from '@/components/helpers/modal'
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
      modal
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
