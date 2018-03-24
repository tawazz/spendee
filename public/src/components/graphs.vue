<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header" :data-background-color="color">
                <div v-show="data_available" id="morris-line-chart" />
                <div v-show="!data_available" class="nodata">
                   No Data Available
                </div>
            </div>
            <div class="card-content">
                <h4 class="card-title capitalize">Daily {{type}}s for {{ nav.display }}</h4>
                <p class="category" v-if="false">
                    <span class="text-danger"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in spending</p>
            </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="type != 'income'">
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
                  <div v-show="tag_data_available" id="morris-pie-chart" ></div>
                  <div v-show="!tag_data_available" class="nodata">
                     No Data Available
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
  import Chart from 'chart.js'
  import moment from 'moment'
  import Morris from "morris"
  import filters from '@/filters'
  import {apis} from '@/hooks'
  import { mapState } from 'vuex'
  import randomColor from 'randomcolor'
  import _ from 'lodash'

  export default {
    name:'graph',
    props:["data","color","type","tagChart"],
    data:function () {
      return {
        line_data:[],
        pie_data:[],
        month:"",
        data_available:false,
        tag_data_available:false
      }
    },
    watch:{
      data:function () {
        let vm = this;
        let data = vm.data;
        vm.cal_data_available();
        $.each(data, function (i,D) {
          var cost = 0;
          $.each(D,function (k,v) {
            cost += parseFloat(v.cost);
          });
          vm.line_data.push({day: i, value: cost});
        });
        vm.line_data.sort((a,b) => {
          if (moment(a.day).isAfter(moment(b.day))){
            return 1;
          }
          if (moment(a.day).isBefore(moment(b.day))){
            return -1;
          }
          return 0;
        });
        var cost = 0;
        vm.line_data = vm.line_data.map(line => {
          cost += line.value;
          return {day: line.day, value: cost};
        });

        $('#morris-line-chart').empty();
        setTimeout(()=>{
          vm.drawAreaChart();
        },100);

      },
      tagChart:function () {
        let vm =this;
        Object.keys(vm.tagChart).map(pie => {
          vm.pie_data.push({label: pie, value:vm.tagChart[pie]});
        });
        $('#morris-pie-chart').empty();
        vm.tag_data_available = vm.pie_data.length > 0;
        setTimeout(()=>{
          vm.drawTagChart();
        },100);

      }

    },
    filters,
    methods: {
      drawAreaChart() {
        let vm = this;
        if (vm.line_data.length > 0) {
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
              ${filters.formatMoney(row.value)}
              </div>`;
              return content;
            }
          });
          vm.line_data = [];
        }
      },
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
            formatter:function (y, data) { return filters.formatMoney(y); } ,
            colors,
            resize:true
          });
          vm.pie_data = [];
        }
      },
      cal_data_available:function () {
        this.data_available = _.keys(this.data).length > 0;
      }
    },
    computed:{
      ...mapState({
          nav: state => state.nav
      })
    }

  }
</script>
<style>

</style>
