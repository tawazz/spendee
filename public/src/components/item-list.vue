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

  export default{
    name:'item-list',
    data:function () {
      return {

      }
    },
    props:["expenses"],
    filters:{
      date:function (D) {
        return moment(D).format("dddd, MMMM Do YYYY");
      },
      formatMoney:function(n,c, d, t){
            c = isNaN(c = Math.abs(c)) ? 2 : c;
            d = d == undefined ? "." : d;
            t = t == undefined ? "," : t;
            var s = n < 0 ? "-" : "";
            var i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));
            var j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
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
