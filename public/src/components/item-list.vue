<template>
  <div class="col-sm-12">
      <div v-for="expense,date in expenses" class="panel panel-danger">
          <div class="panel-heading">
              {{ date|date }}
          </div>
            <a href="/expense/id" v-for="exp in expense">
                <div class="panel-footer">
                    <div class="pull-left item-icon" style="background-color:red;">
                      <i class="mdi mdi-food fa-2x" style="padding-left:5px;"></i>
                    </div>
                    <span class="item-name">{{exp.name}}</span>
                    <span class="pull-right"><i class="fa fa-usd item-cost">{{ exp.cost|formatMoney }}</i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
          <div class="panel-footer">
              <span class="pull-left">Total</span>
              <span class="pull-right"><i class="fa fa-usd item-cost">{{ 10|formatMoney }}</i></span>
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
<style>
  .panel-footer {
    padding: 10px 15px;
  }
  .item-name{
    padding: 10px;
    line-height: 3;
    text-transform: capitalize;
  }
  .item-icon{
    width:40px;
    border-radius:50%;
    color:#fff;
  }
  .item-cost{
    margin-top: 8px;
    font-size: 20px;
  }
</style>
