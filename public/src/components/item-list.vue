<template>
  <div class="col-sm-12">
      <div v-for="expense,date in expenses" class="card">
          <div class="card-heading" data-background-color="red">
              <h3 class="text-center">{{ date|date }}</h3>
          </div>
            <a href="/expense/id" v-for="exp in expense">
                <div class="card-footer capitalize">
                    <span >{{exp.name}}</span>
                    <span class="pull-right"><i class="fa fa-usd">{{ exp.cost|formatMoney }}</i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
          <div class="card-footer">
              <span class="pull-left">Total</span>
              <span class="pull-right"><i class="fa fa-usd">{{ 10|formatMoney }}</i></span>
              <div class="clearfix"></div>
          </div>
      </div>
  </div>
</template>
<script>
  import moment from 'moment'
  import filters from '@/filters'
  export default{
    name:'item-list',
    data:function () {
      return {

      }
    },
    props:["expenses"],
    filters,
    mounted:function () {

    }
  }
</script>
<style scoped>
  .capitalize{
    text-transform: capitalize;
  }
</style>
