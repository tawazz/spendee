<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon" data-background-color="rose">
            <i class="fa-2x mdi mdi-tag-multiple"></i>
          </div>
          <div class="card-content">
            <h4 class="card-title">Expenses on {{ tag.name }}</h4>
            <div class="row">
              <div class="col-md-6">
                <div v-show="showChart" id="morris-pie-chart" ></div>
                <div v-show="!showChart" class="nodata">
                   No Data Available
                </div>
              </div>
              <div class="col-md-6">
                <div class="table-responsive table-sales">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Tag</th>
                        <th>Expenses</th>
                        <th>Amount</th>
                        <th>Percentage</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(value,key) in tagData">
                        <td>
                          <router-link :to="{ name: 'Tags',params: { id: value.id }}">
                            {{ key }}
                          </router-link>
                        </td>
                        <td class="text-center"> {{ value.expenses.length }}</td>
                        <td> $ {{ value.amount|formatMoney }}</td>
                        <td> {{ (value.amount/total_exp) * 100|formatMoney }}%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import {axios,apis} from '@/hooks'
import moment from 'moment'
import filters from '@/filters'
import Morris from "morris"
import randomColor from 'randomcolor'
export default {
  filters,
  beforeRouteEnter (to, from, next) {
    let year = to.params.year;
    let month = to.params.month;

    axios.get(apis.tagData(year,month,null,true)).then(response => {
      next(vm => {
        vm.$store.dispatch('updateNav',{
          year,
          month,
          day:null
        });
        vm.$store.dispatch('loading');
        vm.$store.dispatch('updatePage',"tags");
        vm.tagData = response.data;
        vm.$store.dispatch('done');
      });
    });
  },
  data:function () {
    return {
      tagData: [],
      tag:{
        name:"All Tags"
      },
      pie_data:[],
      showChart:false
    }
  },
  watch:{
    tagData:function () {
      this.initPieData();
    }
  },
  methods:{
    drawTagChart(){
      let vm =this;
      if (vm.pie_data.length > 0) {
        let colors = randomColor({
          count: vm.pie_data.length,
          luminosity: 'light',
          hue: '#ec407a'
        });
        new Morris.Donut({
          element: 'morris-pie-chart',
          data: vm.pie_data,
          formatter:function (y, data) { return '$'+filters.formatMoney(y); } ,
          colors,
          resize:true
        });
        vm.showChart = true;
        vm.pie_data = [];
      }
    },
    initPieData(){
      let vm = this;
      console.log(vm.tagData);
      var data = []
      Object.keys(vm.tagData).map(tag =>{
        data.push({label: tag, value:vm.tagData[tag].amount});
      });
      vm.pie_data = data;
      vm.drawTagChart();
    }
  },
  computed:{
    ...mapState({
      total_exp: state => state.totals.expenses
    })
  },
  mounted:function () {

  }
}
</script>

<style lang="css">
  td{
    vertical-align: middle;
  }
</style>
