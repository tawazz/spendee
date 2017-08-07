<template lang="html">
  <div id="spinnerLoader" v-show="isLoading">
    <div class="container">
      <div class="row" >
        <div class="col-lg-12">
          <i class="fa fa-4x fa-spinner fa-spin"></i>
        </div>
        <div class="col-lg-12" style="margin-top:20px;">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name:'spinnerLoader',
  props:{
      isLoading:{
          default:function () {
              return false;
          }
      }
  }
}
</script>

<style lang="css">
  #spinnerLoader {
    width: 100%;
    text-align: center;
    padding: 1em 0;
  }
</style>
