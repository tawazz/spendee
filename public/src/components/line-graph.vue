<template>
  <div class="row">
    <div class="col-sm-12">
      <div class="card card-chart" data-count="15">
          <div class="card-header" data-background-color="red">
              <canvas class="ct-chart" ref="incomeGraph"></canvas>
          </div>
          <div class="card-content">
              <h4 class="card-title">Daily Expenses</h4>
              <p class="category">
                <span class="text-success">
                  <i class="fa fa-long-arrow-up"></i>55% </span> increase in today sales.</p>
          </div>
          <div class="card-footer">
              <div class="stats">
                  <i class="material-icons">access_time</i> updated 4 minutes ago
              </div>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Chart from 'chart.js';
  export default {
    name:'graph',
    data:function () {
      return {
        graph:null
      }
    },
    mounted:function () {
      let vm =this;
      let days = ["M","T","W","T","F","S","S"];
      let expenses = [];
      for (var i = 0; i < 7; i++) {
        expenses.push(Math.floor((Math.random() * 100) + 1));
      }
      let ctx = vm.$refs.incomeGraph;
      vm.graph = new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Expenses',
                data: expenses,
                borderColor: [
                    '#fff'
                ],
                fill:false,
                borderWidth: 3,
                pointBackgroundColor:'#fff',
                pointBorderColor:'#fff',
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        fontColor:"#fff",
                    },
                    gridLines:{
                      borderDash:[1,3],
                      color:'#fff',
                      zeroLineColor:"#fff",
                    }
                }],
                xAxes: [{
                  ticks: {
                      beginAtZero:true,
                      fontColor:"#fff",
                  },
                  gridLines:{
                    borderDash:[1,3],
                    color:'#fff',
                    zeroLineColor:"#fff"
                  }
                }],
            },
            legend:{
              position:'bottom',
              labels:{
                fontColor: '#fff'
              }
            }
        }
      });
    }
  }
</script>
<style >

</style>
