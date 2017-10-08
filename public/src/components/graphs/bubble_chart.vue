<template lang="html">
  <div class="card card-chart" data-count="15">
      <div class="card-header" :data-background-color="color">
          <canvas class="ct-chart" ref="bubble_chart"></canvas>
      </div>
      <div class="card-content">
          <h4 class="card-title">Tag Overview</h4>
          <p class="category">
            <span class="text-success">
              <i class="fa fa-long-arrow-up"></i>55% </span> increase in today sales.</p>
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
      default: 'blue'
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
