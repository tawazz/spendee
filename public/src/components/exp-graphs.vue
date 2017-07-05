<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="red">
                <div id="morris-line-chart" />
            </div>
            <div class="card-content">
                <h4 class="card-title">Daily Expenses for {{ month }}</h4>
                <p class="category">
                    <span class="text-danger"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in spending</p>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Expense Tags</h4>
            </div>
            <div class="card-content">
                <ul class="nav nav-pills nav-pills-warning">
                    <li class="active">
                        <a href="#pill1" data-toggle="tab" aria-expanded="true">Profile</a>
                    </li>
                    <li class="">
                        <a href="#pill2" data-toggle="tab" aria-expanded="false">Settings</a>
                    </li>
                    <li class="">
                        <a href="#pill3" data-toggle="tab" aria-expanded="false">Options</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="pill1">
                        Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate B2C users after installed base benefits.
                        <br>
                        <br> Dramatically visualize customer directed convergence without revolutionary ROI. Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate B2C users after installed base benefits.
                        <br>
                        <br> This is very nice.
                    </div>
                    <div class="tab-pane" id="pill2">
                        Efficiently unleash cross-media information without cross-media value. Quickly maximize timely deliverables for real-time schemas.
                        <br>
                        <br>Dramatically maintain clicks-and-mortar solutions without functional solutions.
                    </div>
                    <div class="tab-pane" id="pill3">
                        Completely synergize resource taxing relationships via premier niche markets. Professionally cultivate one-to-one customer service with robust ideas.
                        <br>
                        <br>Dynamically innovate resource-leveling customer service for state of the art customer service.
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
  import Morris from 'morris-js-module'
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
      let data =[];
      vm.month = moment(new Date()).format('MMM YYYY');
      for (var i = 0; i < 15; i++) {
        days.push("2017-07-"+ Math.floor((Math.random() * 30) + 1));
        data.push({day: days[i], value: Math.floor((Math.random() * 1000) + 1)});
      }
      new Morris($).Area({
      element: 'morris-line-chart',
      data: data,
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
      resize:true
      });
    }

  }
</script>
<style scoped>
  @import "../../static/pills.css";
</style>
