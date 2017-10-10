<template lang="html">
  <div class="card">
      <div class="card-header card-header-icon" :data-background-color="color">
          <i class="fa-2x mdi mdi-tags"></i>
      </div>
      <div class="card-content">
          <h4 class="card-title">{{ title }}</h4>
          <canvas class="ct-chart" ref="bubble_chart"></canvas>
      </div>
  </div>
</template>

<script>
import Chart from 'chart.js';
import {_,filters} from '@/hooks';
export default {
  props:{
    title:{
      type: String,
      required:false
    },
    options:{
      type: Array,
      required:false
    },
    color:{
      type:String,
      default: 'rose'
    }
  },
  data:function () {
    return {
      bubbleChart: null
    }
  },
  methods: {
    drawChart() {
      let vm =this;
      let ctx = this.$refs.bubble_chart;
      var options = {
          type: 'bubble',
          options: {
              legend: {
                  display: false
              },
              scales: {
                  xAxes: [{
                      display: false,
                      gridLines: {
                          display: false,
                          drawBorder: false
                      },
                      ticks: {
                          min: 0
                      }
                  }],
                  yAxes: [{
                      display: false,
                      gridLines: {
                          display: false
                      },
                      ticks: {
                          min: 0
                      }
                  }]
              }
          },
          data: {
              datasets: vm.options
          }
      }
      vm.bubbleChart = new Chart(ctx, options);
    }
  },
  watch:{
    options: function () {
      let vm =this;
      if (!_.isNil(vm.options)) {
        if (!_.isNil(vm.bubbleChart)) {
          vm.bubbleChart.destroy();
        }
        vm.drawChart();
      }
    }
  }
}
</script>

<style lang="css">
</style>
