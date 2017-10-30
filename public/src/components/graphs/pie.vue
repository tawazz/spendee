<template lang="html">
  <div>
      <div v-show="showChart" ref="pie_chart" :id="id" ></div>
      <div v-show="!showChart" class="nodata">
         No Data Available.
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
        $(`#${vm.$refs.pie_chart.id}`).empty();
        if (vm.options.data.length > 0) {
          vm.showChart = true;
          setTimeout(()=> {
            new Morris.Donut(vm.options);
          },100)
        } else {
          vm.showChart = false;
        }
      },
    },
    mounted:function () {
      this.drawChart();
    }
  }
</script>

<style lang="css">
</style>
