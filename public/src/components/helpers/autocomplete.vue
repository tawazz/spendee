<template lang="html">
  <input type="text"  @input="onInput" ref="autocomplete">
</template>

<script>
import Awesomplete from 'awesomplete'
export default {
  props:['list','onInput','onSelect'],
  data:function () {
    return {
      awesomplete:null
    }
  },
  watch:{
    list:function () {
      let vm = this;
      vm.awesomplete.list = vm.list;
    }
  },
  mounted:function(){
    let vm = this;
    vm.awesomplete = new Awesomplete(vm.$refs.autocomplete,{
      list: [],
      minChars: 3
    });
    $(vm.$refs.autocomplete).on('awesomplete-selectcomplete',function(e){
      vm.onSelect(e);
    });
  }
}
</script>

<style lang="css">
.awesomplete{
  width: 100%;
}
</style>
