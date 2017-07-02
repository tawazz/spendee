<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-chart" data-count="15">
          <div class="card-header" data-background-color="red">
              <canvas class="ct-chart" ref="incomeGraph"></canvas>
          </div>
          <div class="card-content">
              <h4 class="card-title">Daily Expenses for {{ month }}</h4>
              <p class="category">
                  <span class="text-danger"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in spending</p>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Chart from 'chart.js'
  import moment from 'moment'
  export default {
    name:'graph',
    data:function () {
      return {
        month:""
      }
    },
    mounted:function () {
      let vm =this;
      let days = [];
      let expenses = [];
      vm.month = moment(new Date()).format('MMM YYYY');
      for (var i = 0; i < 31; i++) {
        days.push(i);
        expenses.push(Math.floor((Math.random() * 1000) + 1));
      }
      let ctx = vm.$refs.incomeGraph;
      vm.graph = new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Expenses',
                data: expenses,
                borderColor:'#fff',
                backgroundColor:'rgba(255,255,255,0.4)',
                borderWidth: 5,
                pointBackgroundColor:'#fff',
                pointBorderColor:'#fff',
                pointBorderWidth:3,
                pointRadius:0,
                lineTension:0,
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
                      color:'#ccc',
                      zeroLineColor:"#fff",
                      drawOnChartArea:false
                    }
                }],
                xAxes: [{
                  ticks: {
                      beginAtZero:true,
                      fontColor:"#fff",
                  },
                  gridLines:{
                    borderDash:[1,1],
                    color:'#ccc',
                    zeroLineColor:"#fff",
                    drawOnChartArea:false
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
