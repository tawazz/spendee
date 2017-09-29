<template lang="html">
  <div class="container">
    <div class='row'>
      <div class="col-md-12">
        <bar-chart id="year-bar-chart" title="Balance Overview" :options="barChartOptions" />
      </div>
    </div>
  </div>
</template>

<script>
import barChart from "@/components/graphs/bar_chart"
import {_,moment,randomColor,apis,axios,utils,store,filters} from '@/hooks'

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
      this.drawBarChart();
      var year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      vm.$store.dispatch('updateNav',{
        year,
        month:null,
        day:null
      });
      vm.$store.dispatch('updatePage',"overview");
      vm.$store.dispatch('done');
    },
    drawBarChart(){
      let vm =this;
      var year = _.isNil(vm.$route.params.year) ? new Date().getFullYear() : vm.$route.params.year;
      vm.$store.dispatch('loading');
      axios.get(apis.overview(year)).then((response)=>{
        vm.overviewData = response.data;
        vm.updateBarOptions(year);
        vm.$store.dispatch('done');
      }).catch((error)=>{
        utils.error_handler(vm,error);
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
        var pos = (bal >= 0 )? bal : 0;
        var neg = (bal < 0 )? bal : 0;
        bars[i-1] = {date,pos,neg}
      }

      let barColors = randomColor({
        count: 1,
        hue: '#ec407a'
      });

      vm.barChartOptions = {
        element: 'year-bar-chart',
        data:bars,
        xkey: 'date',
        ykeys: ['pos', 'neg'],
        labels: ['Balance'],
        barColors: [randomColor({
          uminosity: 'bright',
          hue: '#43a047'
        }),randomColor({
          uminosity: 'bright',
          hue: '#ec407a'
        })],
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
          content = `<div class="morris-hover-row-label">${filters.monthYear(row.date)}</div><div class='morris-hover-point' style='color: #03a9f4'>
          Balance: ${bal}

          </div>`;
          return content;
        },
        resize:true
      };
    }
  }
}
</script>

<style lang="css">
</style>
