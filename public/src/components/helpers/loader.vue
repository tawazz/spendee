<template lang="html">
  <modal :isOpen="isLoading" small id="spinnerLoader">
    <div slot="header"></div>
    <div slot="footer"></div>
    <div v-show="isLoading">
      <div class="row" >
        <div class="col-lg-12">
          <div class="loader"></div>
        </div>
        <div class="col-lg-12" style="margin-top:20px;">
          <slot>Loading...</slot>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
export default {
  name:'spinnerLoader',
  props:{
      isLoading:{
          default:function () {
              return false;
          }
      }
  },
  components:{
    modal
  }
}
</script>

<style lang="css" scoped>
  #spinnerLoader {
    width: 100%;
    text-align: center;
    padding: 1em 0;
  }
  .loader {
  font-size: 10px;
  margin: 0 auto;
  text-indent: -9999em;
  width: 10em;
  height: 10em;
  border-radius: 50%;
  background: #ffffff;
  background: -moz-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
  background: -webkit-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
  background: -o-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
  background: -ms-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
  background: linear-gradient(to right, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
  position: relative;
  -webkit-animation: load3 1.4s infinite linear;
  animation: load3 1.4s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.loader:before {
  width: 50%;
  height: 50%;
  background: #eb4744;
  border-radius: 100% 0 0 0;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
}
.loader:after {
  background: #f5f5f5;
  width: 75%;
  height: 75%;
  border-radius: 50%;
  content: '';
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
@-webkit-keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

</style>
