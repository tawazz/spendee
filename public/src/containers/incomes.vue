<template >
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <item-list :data="expdata.exp_data" color="green" type="income" />
      </div>
      <div class="col-md-6">
        <exp-graphs :expenses="expdata.exp_data" />
      </div>
      <div class="btn-add">
        <a href="javascript:void(0)" class="btn btn-danger btn-fab"><i class="material-icons mdi mdi-plus"></i><div class="ripple-container"></div></a>
      </div>
    </div>
  </div>
</template>
<script>
  import itemsList from '@/components/item-list'
  import graphs from '@/components/exp-graphs'
  const expdata =  require('../../static/data.json');

  export default {
    name:'expenses',
    data:function () {
      return {
        expdata:[]
      };
    },
    components:{
      'item-list':itemsList,
      'exp-graphs':graphs
    },
    mounted:function () {
      let vm =this;
      vm.expdata = expdata;
    }
  }
</script>
<style media="screen">
  .btn.btn-fab i.material-icons, .input-group-btn .btn.btn-fab i.material-icons {
    line-height: 16px;
  }
  .btn-add{
    position: fixed;
    bottom: 0px;
    right: 0px;
    padding: 50px;
  }
</style>
