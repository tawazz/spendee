<template>
  <div>
      <div v-for="item,date in data" class="card">
          <div class="card-heading" :data-background-color="color">
              <h3 class="text-center">{{ date|date }}</h3>
          </div>
            <a :href="`/${type}/${i.user_id}`" :style="{color}" v-for="i in item">
                <div class="card-footer capitalize">
                    <span >{{i.name}}</span>
                    <span class="pull-right"><i class="fa fa-usd">{{ i.cost|formatMoney }}</i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
          <div class="card-footer">
              <span class="pull-left">Total</span>
              <span class="pull-right"><i class="fa fa-usd">{{ total(item)|formatMoney }}</i></span>
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
    props:["data","color","type"],
    filters,
    methods:{
        total:function (item) {
            return item.reduce((a, b) => {return parseFloat(a) + parseFloat(b.cost); }, 0);
        }
    },
    mounted:function () {

    }
  }
</script>
<style scoped>
  .capitalize{
    text-transform: capitalize;
  }
</style>
