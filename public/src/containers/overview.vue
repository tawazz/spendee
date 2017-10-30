<template lang="html">
  <div class="container">
    <div class='row'>
      <div class="col-md-12">
        <bar-chart id="year-bar-chart" title="Balance Overview" :options="barChartOptions" />
      </div>
      <div class="col-md-6">
        <line-chart id="year-line-chart" title="Year Overview" :options="lineChartOptions" />
      </div>
      <div class="col-md-6">
        <bar-chart id="inc-bar-chart" title="Income Overview" color="green" :options="incBarChartOptions" />
      </div>
      <div class="col-md-12">
        <bubble-chart title="Tags Overview" :options="bubble_chartData" />
      </div>
    </div>
  </div>
</template>

<script>
import barChart from "@/components/graphs/bar_chart"
import lineChart from "@/components/graphs/line_chart"
import bubble_chart from '@/components/graphs/bubble_chart'
import {_,moment,randomColor,apis,axios,utils,store,filters} from '@/hooks'

export default {
  data:function () {
    return {
      barChartOptions: null,
      lineChartOptions:null,
      incBarChartOptions:null,
      bubble_chartData:null,
      overviewData: null
    }
  },
  components:{
    'bar-chart': barChart,
    'line-chart': lineChart,
    'bubble-chart': bubble_chart
  },
  beforeRouteEnter (to, from, next) {
    store.dispatch('loading');
    let year = _.isNil(to.params.year) ? new Date().getFullYear() : to.params.year;
    next(vm =>{
      vm.updatePage();
      vm.$store.dispatch('done');
    });

  },
  watch:{
    $route:function () {
        this.updatePage();
    }
  },
  methods:{
    updatePage(){
      let vm = this;
      store.dispatch('loading');
      this.drawCharts();
      var year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      vm.$store.dispatch('updateNav',{
        year,
        month:null,
        day:null
      });
      vm.$store.dispatch('updatePage',"overview");
      vm.$store.dispatch('done');
    },
    drawCharts(){
      let vm =this;
      var year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      vm.$store.dispatch('loading');
      axios.get(apis.overview(year)).then((response)=>{
        vm.overviewData = response.data;
        vm.updateBarOptions(year);
        vm.updateLineOptions(year);
        vm.updateIncChartOptions();
        vm.updateBubbleChart();
        vm.$store.dispatch('done');
      }).catch((error)=>{
        utils.error_handler(vm,error);
        vm.$store.dispatch('done');
      });
    },
    updateLineOptions(year){
      let vm = this;
      let bars = [];
      for (var i = 1; i < 13; i++) {
        var date = moment(`${year}-${i}-1`,'YYYY-M-D').format('YYYY-M-D');
        var exp = vm.overviewData.expenses[i];
        var inc = vm.overviewData.incomes[i];
        var bal = inc - exp;
        bars[i-1] = {date,exp,inc,bal}
      }

      vm.lineChartOptions = {
        element: 'year-line-chart',
        data:bars,
        xkey: 'date',
        ykeys: ['inc', 'exp', 'bal'],
        labels: ['Expense','Incomes','Balance'],
        lineColors: ["#6ffc76","#e2769a","#333333"],
        preUnits:'$',
        hoverCallback: function (index, options, content, row) {
          let bal = 0;
          if (row.bal > 0) {
            bal = `$${filters.formatMoney(row.bal)}`
          }else if(row.bal < 0) {
            bal = `-$${filters.formatMoney(row.bal * -1)}`
          }else{
            bal = `$${filters.formatMoney(0)}`
          }
          content = `<div class="morris-hover-row-label">${filters.monthYear(row.date)}</div><div class='morris-hover-point' style='color: #03a9f4'>
          Balance:${bal}

          </div>`;
          return content;
        },
        goalLineColors:['#d9edf7'],
        dateFormat: function (x) { return moment(x).format(" MMMM YYYY"); },
        preUnits:'$',
        xLabelFormat:function (x) { return moment(x).format("MMM"); },
        resize:true
      };
    },
    updateBarOptions(year){
      let vm = this;
      let bars = [];
      for (var i = 1; i < 13; i++) {
        var date = moment(`${year}-${i}-1`,'YYYY-M-dddd').format('MMM YYYY');
        var exp = vm.overviewData.expenses[i];
        var inc = vm.overviewData.incomes[i];
        var bal = inc - exp;
        var pos = (bal >= 0 )? bal : 0;
        var neg = (bal < 0 )? bal : 0;
        bars[i-1] = {date,pos,neg}
      }

      vm.barChartOptions = {
        element: 'year-bar-chart',
        data:bars,
        xkey: 'date',
        ykeys: ['pos', 'neg'],
        labels: ['Balance'],
        barColors: ["#6ffc76","#e2769a"],
        preUnits:'$',
        hoverCallback: function (index, options, content, row) {
          let bal = 0;
          if (row.pos > 0) {
            bal = `$${filters.formatMoney(row.pos)}`
          }else if(row.neg < 0) {
            bal = `-$${filters.formatMoney(row.neg * -1)}`
          }else{
            bal = `$${filters.formatMoney(0)}`
          }
          content = `<div class="morris-hover-row-label">${row.date}</div><div class='morris-hover-point' style='color: #03a9f4'>
          Balance: ${bal}

          </div>`;
          return content;
        },
        resize:true
      };
    },
    updateIncChartOptions(){
      let vm = this;
      let bars = [];
      vm.overviewData.allIncomes.slice(0,5).map(exp =>{
        bars.push(exp);
      });

      vm.incBarChartOptions = {
        element: 'inc-bar-chart',
        data:bars,
        xkey: 'name',
        ykeys: ['cost'],
        labels: ['Balance'],
        barColors: ["#6ffc76"],
        preUnits:'$',
        hoverCallback: function (index, options, content, row) {
          content = `<div class="morris-hover-row-label">${row.name}</div><div class='morris-hover-point' style='color: #03a9f4'>
          $${filters.formatMoney(row.cost)}
          </div>`;
          return content;
        },
        resize:true
      };
    },
    updateBubbleChart(){
      let vm = this;
      let data = [];
      let i =1;
      let total  = 0;
      Object.keys(vm.overviewData.tags).map(tag => {
        total += vm.overviewData.tags[tag];
      });
      Object.keys(vm.overviewData.tags).map(tag =>{
        var backgroundColor = randomColor({
           luminosity: 'light',
           hue:"#ff6384",
           format: 'rgba'
        });
        var r = Math.round((vm.overviewData.tags[tag]/total )*100)*5
        data.push({
            label:tag,
            data: [{
                x: i,
                y: vm.overviewData.tags[tag],
                r
            }],
            backgroundColor,
            borderColor: backgroundColor,
            hoverBackgroundColor: "transparent",
            borderWidth: 8,
        });
        i+=(1+r*2);
      });
      vm.bubble_chartData = data;
    }
  }
}
</script>

<style lang="css">
</style>
