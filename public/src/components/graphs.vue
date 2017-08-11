<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header" :data-background-color="color">
                <div id="morris-line-chart" />
            </div>
            <div class="card-content">
                <h4 class="card-title capitalize">Daily {{type}}s for {{ nav.display }}</h4>
                <p class="category">
                    <span class="text-danger"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in spending</p>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div v-if="type=='income'" class="card-header card-header-icon" :data-background-color="color">
              <i class="fa-2x mdi mdi-tag-multiple"></i>
            </div>
            <div v-else class="card-header card-header-icon" data-background-color="rose">
                <i class="fa-2x mdi mdi-tag-multiple"></i>
            </div>
            <div class="card-content">
              <h4 class="card-title">Tags Overview</h4>
              <div class="row">
                <div class="col-sm-12">
                  <div id="morris-pie-chart" ></div>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
  </div>
</template>
<script>
  import Chart from 'chart.js'
  import moment from 'moment'
  import Morris from "morris"
  import filters from '@/filters'
  import {apis} from '@/hooks'
  import { mapState } from 'vuex'
  import randomColor from 'randomcolor'

  export default {
    name:'graph',
    props:["areaChart","color","type","tagChart"],
    data:function () {
      return {
        line_data:[],
        pie_data:[],
        month:""
      }
    },
    watch:{
      areaChart:function () {
        let vm = this;
        let exp = vm.areaChart;
        for (var date_exp in exp) {
           var D = date_exp;
           var cost = 0;
           $.each(exp[D],function (k,v) {
             cost += parseFloat(v.cost);
           });
           vm.line_data.push({day: D, value: cost});
        }
        $('#morris-line-chart').empty();
        vm.drawAreaChart();
      },
      tagChart:function () {
        let vm =this;
        Object.keys(vm.tagChart).map(pie => {
          vm.pie_data.push({label: pie, value:vm.tagChart[pie]});
        });
        $('#morris-pie-chart').empty();
        vm.drawTagChart();
      }

    },
    filters,
    methods: {
      drawAreaChart() {
        let vm = this;
        new Morris.Area({
          element: 'morris-line-chart',
          data: vm.line_data,
          xkey: 'day',
          ykeys: ['value'],
          labels: ['Spent'],
          yLabelFormart: function (y) { return "$"+y; },
          dateFormat: function (x) { return moment(x).format("dddd, MMMM Do YYYY"); },
          preUnits:'$',
          xLabelFormat:function (x) { return moment(x).format("MMM Do"); },
          lineColors:['#fff'],
          goalLineColors:['#f5f5f5'],
          gridTextColor:"#fff",
          fillOpacity:"0.4",
          grid:false,
          resize:true,
          hoverCallback: function (index, options, content, row) {
            content = `<div class="morris-hover-row-label">${filters.date(row.day)}</div><div class='morris-hover-point' style='color: #03a9f4'>
              Spent:
              $ ${filters.formatMoney(row.value)}
            </div>`;
            return content;
          }
        });
        vm.line_data = [];
      },
      drawTagChart(){
        let vm =this;
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
        vm.pie_data = [];
      }
    },
    computed:{
      ...mapState({
          nav: state => state.nav
      })
    }

  }
</script>
<style scoped>
</style>
