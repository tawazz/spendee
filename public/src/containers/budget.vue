<template lang="html">
  <div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2>Budgets</h2>
        </div>
        <div class="col-sm-12">
          <button v-if="showAddButton" style="margin-bottom: 20px;" type="button" @click="addBudget" class="btn btn-info btn-raised">
              <i class="fa fa-plus"></i> Add Budget
          </button>
        </div>
    </div>
    <div class="row">
       <div v-if="budgets.length < 1" class="col-xs-12">
         <div class="panel panel-info">
             <div class="panel-heading">
                 <h2 class="text-center" style="text-transform:capitalize">No Budget Data Available</h2>
             </div>
             <div class="panel-body" style="height:300px;display: flex;justify-content: center; align-items: center;" >
                 <h4 class="empty-content">Its Lonely Out here</h4>
             </div>
          </div>
       </div>
        <div v-for="budget in budgets" class="col-md-6 col-sm-12">
          <div class="panel panel-info">
              <div class="panel-heading">
                  <h2 style="text-transform:capitalize">{{budget.name}}</h2>
              </div>
              <div class="alert alert-info">
                <div class="row">
                  <div class="col-xs-6">
                    <h4>Spent</h4>
                    <p>${{budget.spent|formatMoney}}</p>
                  </div>
                  <div class="col-xs-6">
                    <h4>Budgeted</h4>
                    <p>${{budget.amount|formatMoney}}</p>
                  </div>
                </div>
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body" >
                <div v-if="budget.expired">
                  <div v-if="budget.amount >= budget.spent">
                    <p>
                      You Saved
                    </p>
                    <p class="fa-2x text-success">
                      ${{budget.saved|formatMoney}}
                    </p>
                    <p>
                      this month!
                    </p>
                    <div class="progress progress-striped budget-progress">
                      <div class="progress-bar progress-bar-success" :style="{width:`${budget.spentPercentage}%`}">{{budget.spentPercentage}}%</div>
                    </div>
                  </div>
                  <div v-else>
                    <p>
                      You over spent by
                    </p>
                    <p class="fa-2x text-danger">
                      ${{budget.saved|formatMoney}}
                    </p>
                    <p>
                      this month!
                    </p>
                    <div class="progress progress-striped budget-progress">
                      <div class="progress-bar progress-bar-danger" style="width:100%">{{budget.spentPercentage}}%</div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <div v-if="budget.spendingLeft >= 0">
                    <p>
                      You can keep spending
                    </p>
                    <p class="fa-2x text-success">
                      ${{budget.spendingLeft|formatMoney}}
                    </p>
                    <p>
                      each day!
                    </p>
                    <div class="progress progress-striped budget-progress">
                      <div class="progress-bar progress-bar-success" :style="{width:`${budget.spentPercentage}%`}">{{budget.spentPercentage}}%</div>
                    </div>
                  </div>
                  <div v-else>
                    <p>
                      Opps! you went over budget by
                    </p>
                    <p class="fa-2x text-danger">
                      ${{(budget.spent - budget.amount)|formatMoney}}
                    </p>
                    <p>
                      spend carefully!!!
                    </p>
                    <div class="progress progress-striped budget-progress">
                      <div class="progress-bar progress-bar-danger" style="width:100%">{{budget.spentPercentage}}%</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.panel-body -->
              <div class="panel-footer">
                <h2 class="text-center">Budgeted Tags</h2>
                  <pie-chart :id="`morris-pie-chart-tags-${budget.id}`" :options="budget_tags[`_${budget.id}`]"/>
              </div>
              <div v-if="!budget.expired" class="panel-footer">
                  <a class="btn btn-info" href="#" @click.prevent="edit(budget.id)" >Edit</a>
                  <a class="btn btn-danger" href="#" @click.prevent="deleteBudget(budget.id)">Delete</a>
              </div>
          </div>
          <!-- /.panel -->
        </div>
    </div>
    <add-budget :show="showAddModal" :save="saveBudget" :close="closeAddModal" :selected_budget="selected_budget"/>
  </div>
</template>

<script>
  import PieChart from "@/components/graphs/pie"
  import addBudgetModal from "@/components/budgets/add"
  import { mapState } from 'vuex'
  import {axios,apis,utils,filters,randomColor} from '@/hooks'
  export default {
    components:{
      'pie-chart': PieChart,
      'add-budget': addBudgetModal
    },
    beforeRouteEnter (to, from, next) {
      let year = to.params.year;
      let month = to.params.month;

      next(vm => {
        vm.$store.dispatch('updateNav',{
          year,
          month,
          day:null
        });
        vm.updatePage(year,month);
        vm.$store.dispatch('updatePage',"budgets");
      });

    },
    watch:{
        $route:function () {
          let vm = this;
          let params = vm.$route.params;
          let year = params.year;
          let month = params.month;
          vm.updatePage(year,month);
        }
    },
    data: function () {
      return {
        budgets:[],
        budget_tags:{},
        showAddModal:false,
        selected_budget: null
      }
    },
    computed:{
      showAddButton: function () {
        let vm = this;
        let params = vm.$route.params;
        let year = params.year;
        let month = params.month;
        if (_.isNil(year) || _.isNil(month)) {
          return true;
        }
        let current_period = moment().format('M-YYYY');
        let nav_period = `${month}-${year}`;
        return current_period == nav_period;
      }
    },
    filters,
    methods:{
      addBudget(){
          this.showAddModal = true;
      },
      closeAddModal(){
        this.showAddModal = false;
      },
      saveBudget(){
        this.closeAddModal();
        this.updatePage();
      },
      chartOptions(budget){
        let vm = this;
        let tags = budget.tags;
        let tag_count = Object.keys(tags).length;

        if (tag_count > 0) {
          let colors = randomColor({
            count: tag_count,
            luminosity: 'light',
            hue: '#ec407a'
          });
          let data = [];
          Object.keys(tags).map(tag => {
            return data.push({label: tag, value:budget.tags[tag]});
          });
          vm.budget_tags[`_${budget.id}`]={
            element: `morris-pie-chart-tags-${budget.id}`,
            data,
            formatter:function (y, data) { return '$'+filters.formatMoney(y); } ,
            colors,
            resize:true
          };
        }else{
          vm.budget_tags[`_${budget.id}`]={data:[]};
        }
      },
      edit(id){
        let vm = this;
        vm.$store.dispatch('loading');
        axios.get(apis.budget(id)).then(response => {
          vm.selected_budget = response.data;
          vm.showAddModal= true;
          vm.$store.dispatch('done');
        }).catch(error => {
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
      },
      deleteBudget(id){
        let vm = this;
        vm.$store.dispatch('loading');
        axios.delete(apis.budget(id)).then(response => {
          vm.updatePage();
          vm.$store.dispatch('done');
        }).catch(error => {
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
      },
      updatePage(year = null,month = null){
        let vm = this;

        if (_.isNil(year) && _.isNil(month)) {
          let params = vm.$route.params;
          let year = params.year;
          let month = params.month;
        }
        vm.$store.dispatch('loading');
        axios.get(apis.budgets(year,month)).then(response => {
          vm.budgets = response.data;
          vm.budgets.map(budget => {
            vm.chartOptions(budget)
          });
          vm.$store.dispatch('done');
        }).catch((error)=>{
          utils.error_handler(vm,error);
          vm.$store.dispatch('done');
        });
      }
    }
  }
</script>

<style lang="css">
</style>
