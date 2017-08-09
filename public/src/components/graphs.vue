<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header" :data-background-color="color">
                <div id="morris-line-chart" />
            </div>
            <div class="card-content">
                <h4 class="card-title capitalize">Daily {{type}}s for {{ month }}</h4>
                <p class="category">
                    <span class="text-danger"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in spending</p>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-12">
        <div class="card card-stats">
            <div class="card-header card-header-icon" data-background-color="orange">
                <i class="fa-2x mdi mdi-tag-multiple"></i>
            </div>
            <h4 class="card-title">Tags Overview</h4>
            <div class="clearfix"></div>
            <div class="card-content">
              <div class="row">
                <div class="col-sm-12">
                  <div id="morris-pie-chart" ></div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <h4>here</h4>
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

  export default {
    name:'graph',
    props:["data","color","type"],
    data:function () {
      return {
        line_data:[],
        month:""
      }
    },
    watch:{
      data:function () {
        let vm = this;
        let exp = vm.data;
        for (var date_exp in exp) {
           var D = date_exp;
           var cost = 0;
           $.each(exp[D],function (k,v) {
             cost += parseFloat(v.cost);
           });
           vm.line_data.push({day: D, value: cost});
        }
        $('#morris-line-chart').empty();
        vm.areaChart();
      }

    },
    filters,
    methods: {
      areaChart() {
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
      }
    },
    mounted:function () {
      let vm =this;
      let days = [];
      vm.month = moment(new Date()).format('MMM YYYY');

      new Morris.Donut({
        element: 'morris-pie-chart',
        data: [
          {label: "Food and Drink", value:200 },
          {label: "Petrol", value:120 },
          {label: "shopping", value:60 },
        ],
        formatter:function (y, data) { return '$'+y; } ,
        colors:["#ffa726","#ff6384","#36a2eb","#4bc0c0"],
        resize:true
      });

    }

  }
</script>
<style scoped>
</style>
