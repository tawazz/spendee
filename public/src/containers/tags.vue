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
                        <th>Expenses</th>
                        <th>Cost</th>
                        <th>Percentage</th>
                      </tr>
                    </thead>
                    <tbody v-for="(value,key) in tagData">
                      <tr v-for="(cost,exp) in value.spent">
                        <td class="text-capitalize"> {{ exp }}</td>
                        <td> $ {{ cost|formatMoney }}</td>
                        <td> {{ (cost/value.amount) * 100|round }}%</td>
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
    let id = to.params.id;
    let tagData = () => {
      return axios.get(apis.tagData(id,year,month,null,true));
    };
    let tag = () => {
      return axios.get(apis.tag(id));
    }
    next(vm => {
      vm.$store.dispatch('updateNav',{
        year,
        month,
        day:null
      });
      vm.$store.dispatch('loading');
      axios.all([tagData(),tag()]).then(axios.spread((td,tag) => {
        vm.tagData = td.data;
        vm.tag = tag.data;
        vm.$store.dispatch('updatePage',`tags/${vm.tag.id}`);
        vm.$store.dispatch('done');
      }));
    });

  },
  data:function () {
    return {
      tagData: [],
      tag:{
        id:null,
        name:"All Tags"
      },
      pie_data:[],
      showChart:false
    }
  },
  watch:{
    tagData:function () {
      this.initPieData();
    },
    $route:function () {
      let vm = this;
      let year = vm.$route.params.year;
      let month = vm.$route.params.month;
      let id = vm.$route.params.id;
      axios.get(apis.tagData(id,year,month,null,true)).then(response => {
        vm.tagData = response.data;
      });
    }
  },
  methods:{
    drawTagChart(){
      let vm =this;
      $('#morris-pie-chart').empty();
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
      } else {
        vm.showChart = false;
      }
    },
    initPieData(){
      let vm = this;
      var data = []
      Object.keys(vm.tagData).map(tag =>{
        data.push({label: tag, value:vm.tagData[tag].amount});
      });
      vm.pie_data = data;
      setTimeout(()=>{
        vm.drawTagChart();
      },100);

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
