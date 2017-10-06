<template lang="html">
  <div class="card">
    <div class="card-header card-header-icon" :data-background-color="color">
      <i class="fa-2x mdi mdi-chart-areaspline"></i>
    </div>
    <div class="card-content">
      <h4 class="card-title">{{ title }}</h4>
        <div v-show="showChart" :id="id" ></div>
        <div v-show="!showChart" class="nodata">
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
      showChart: false,
    }
  },
  watch:{
    options: function () {
      let vm =this;
      if (!_.isNil(vm.options)) {
        vm.drawChart();
      }
    }
  },
  methods: {
    drawChart(){
      let vm =this;
      $(`#${vm.id}`).empty();
      if (vm.options.data.length > 0) {
        vm.showChart = true;
        setTimeout(()=> {
          new Morris.Line(vm.options);
        },100)
      } else {
        vm.showChart = false;
      }
    },
  }
}
</script>

<style lang="css">
</style>
