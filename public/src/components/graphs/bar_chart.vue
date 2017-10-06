<template lang="html">
  <div class="card">
    <div class="card-header card-header-icon" :data-background-color="color">
      <i class="fa-2x mdi mdi-chart-bar"></i>
    </div>
    <div class="card-content">
      <h4 class="card-title">{{ title }}</h4>
        <div v-show="showBarChart" :id="id" ></div>
        <div v-show="!showBarChart" class="nodata">
           No Data Available.
        </div>
    </div>
  </div>
</template>

<script>

import {_,Morris} from '@/hooks'

export default {
  props:{
    title:{
      type: String,
      required:false
    },
    options:{
      type: Object,
      required:false
    },
    id:{
      required:true,
      type:String
    },
    color:{
      type:String,
      default: 'rose'
    }
  },
  data: function() {
    let vm = this;
    return {
      showBarChart: false,
    }
  },
  watch:{
    options: function () {
      let vm =this;
      if (!_.isNil(vm.options)) {
        vm.drawBarChart();
      }
    }
  },
  methods: {
    drawBarChart(){
      let vm =this;
      $(`#${vm.id}`).empty();
      if (vm.options.data.length > 0) {
        vm.showBarChart = true;
        setTimeout(()=> {
          new Morris.Bar(vm.options);
        },100)
      } else {
        vm.showBarChart = false;
      }
    },
  }
}
</script>

<style lang="css">
</style>
