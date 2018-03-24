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
              <div class="col-md-6 text-center">
                <div v-show="showPieChart" id="morris-pie-chart" ></div>
                <div v-show="!showPieChart" class="nodata">
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
                        <td> {{ cost|formatMoney }}</td>
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
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon" data-background-color="rose">
            <i class="fa-2x mdi mdi-chart-bar"></i>
          </div>
          <div class="card-content">
            <h4 class="card-title">Year Overview</h4>
              <div v-show="showBarChart" id="morris-bar-chart" ></div>
              <div v-show="!showBarChart" class="nodata">
                 {{ barChartText }}
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
import _ from 'lodash'
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
      vm.initBarData();
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
      showPieChart:false,
      showBarChart:false,
      yearlyData:[],
      barChartText: "No Data Available",
      previousYear: null
    }
  },
  watch:{
    tagData:function () {
      this.initPieData();
    },
    yearlyData:function () {
      this.drawBarChart();
    },
    $route:function () {
      let vm = this;
      let year = vm.$route.params.year;
      let month = vm.$route.params.month;
      let id = vm.$route.params.id;
      axios.get(apis.tagData(id,year,month,null,true)).then(response => {
        vm.tagData = response.data;
      });
      this.initBarData();
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
        setTimeout(()=>{
          new Morris.Donut({
            element: 'morris-pie-chart',
            data: vm.pie_data,
            formatter:function (y, data) { return filters.formatMoney(y); } ,
            colors,
            resize:true
          });
          vm.showPieChart = true;
          vm.pie_data = [];
        },100);
      } else {
        vm.showPieChart = false;
      }
    },
    drawBarChart(){
      let vm =this;
      $('#morris-bar-chart').empty();
      if (vm.yearlyData.length > 0) {
        vm.showBarChart = true;
        let i =0;
        let year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
        let bars = vm.yearlyData.map(data => {
          i++;
          var date = moment(`${year}/${i}/1`,'YYYY/M/dddd').format('MMM/YYYY');
          return {'d': date,'v': _.isArray(data)? 0: data[vm.tag.name]}
        });

        let barColors = randomColor({
          count: 1,
          hue: '#ec407a'
        });
        setTimeout(()=> {
          new Morris.Bar({
            element: 'morris-bar-chart',
            data:bars,
            xkey: 'd',
            ykeys: ['v'],
            labels: ['Expenses'],
            barColors,
            preUnits:'$',
            resize:true
          });
        },100)
      } else {
        vm.showBarChart = false;
      }
    },
    initPieData(){
      let vm = this;
      var data = []
      Object.keys(vm.tagData).map(tag =>{
        data.push({label: tag, value:vm.tagData[tag].amount});
      });
      vm.pie_data = data;
      vm.drawTagChart();
    },
    initBarData(){
      let vm = this;
      vm.barChartText = "No Data Available";
      let year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      if (year != vm.previousYear) {
        vm.previousYear = year;
        vm.yearlyData = [];
        let tagData = () => {
          let req = [];
          for (var i = 1; i < 13; i++) {
            req[i] = axios.get(apis.tagData(vm.$route.params.id,_.toString(year),_.toString(i),null,false));
          }
          return req;
        };
        let barData = [];
        vm.barChartText = "Loading...";
        axios.all(tagData()).then(axios.spread((...args) => {
          for (let i = 1; i < args.length; i++) {
            if (!_.isNil(args[i])) {
              barData.push(args[i].data);
            }
          }
          vm.yearlyData = barData;
          vm.barChartText = "No Data Available";
        }));
      }
    }
  },
  computed:{
    ...mapState({
      total_exp: state => state.totals.expenses
    })
  },
  mounted:function () {
    let vm = this;

  }
}
</script>

<style lang="css">
  td{
    vertical-align: middle;
  }
</style>
