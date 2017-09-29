<template lang="html">
  <div class="container">
    <div class='row'>
      <div class="col-md-12">
        <bar-chart id="year-bar-chart" title="Year Overview" :options="barChartOptions" />
      </div>
    </div>
  </div>
</template>

<script>
import barChart from "@/components/graphs/bar_chart"
import {_,moment,randomColor,apis,axios,utils} from '@/hooks'

export default {
  data:function () {
    return {
      barChartOptions: null,
      overviewData: null
    }
  },
  components:{
    'bar-chart': barChart
  },
  watch:{
    $route:function () {
        this.drawBarChart();
    }
  },
  methods:{
    drawBarChart(){
      let vm =this;
      var year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      vm.$store.dispatch('loading');
      axios.get(apis.overview(year)).then((response)=>{
        vm.overviewData = response.data;
        vm.updateBarOptions(year);
        vm.$store.dispatch('done');
      }).catch((error)=>{
        console.log(error);
        //utils.error_handler(vm,error);
        vm.$store.dispatch('done');
      });
    },
    updateBarOptions(year){
      let vm = this;
      let bars = [];
      for (var i = 1; i < 13; i++) {
        var date = moment(`${year}/${i}/1`,'YYYY/M/dddd').format('MMM/YYYY');
        var exp = vm.overviewData.expenses[i];
        var inc = vm.overviewData.incomes[i];
        var bal = inc - exp;
        bars[i-1] = {date,exp,inc,bal}
      }

      let barColors = randomColor({
        count: 1,
        hue: '#ec407a'
      });

      vm.barChartOptions = {
        element: 'year-bar-chart',
        data:bars,
        xkey: 'date',
        ykeys: ['inc', 'exp' ,'bal'],
        labels: ['Incomes', 'Expenses' , 'Balance'],
        barColors: [randomColor({
          uminosity: 'dark',
          hue: '#43a047'
        }),'#e53935','#60677A'],
        preUnits:'$',
        resize:true
      };
    }
  },
  mounted:function () {
    this.drawBarChart();
  }
}
</script>

<style lang="css">
</style>
