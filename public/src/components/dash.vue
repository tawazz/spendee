<template>
  <div class="container">
    <div class="row">
        <div class="col-xs-12">
          <div class="btn-group" role="group" aria-label="...">
            <router-link :to="{ path:`/${nav.page}/${nav.prev}`}" class="btn" @click.native="previous"><img src="../../../images/left.png"/></router-link>
            <span class="btn text-default" style="margin-top:7px;text-transform: capitalize;">{{ nav.display }}</span>
            <router-link :to="{ path:`/${nav.page}/${nav.next}`}"  @click.native="next" class="btn"><img src="../../../images/right.png"/></router-link>
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
          <div class="card card-stats">
              <div class="card-header" data-background-color="red">
                  <i class="mdi mdi-arrow-up"></i>
              </div>
              <div class="card-content">
                  <p class="category">Expenses</p>
                  <h4 class="card-title text-danger">{{ exp|formatMoney }}</h4>
              </div>
          </div>
      </div>
      <div class="col-sm-4">
          <div class="card card-stats">
              <div class="card-header" data-background-color="green">
                  <i class="mdi mdi-arrow-down"></i>
              </div>
              <div class="card-content">
                  <p class="category">Incomes</p>
                  <h4 class="card-title text-success">{{ inc|formatMoney }}</h4>
              </div>
          </div>
      </div>
      <div class="col-sm-4">
          <div class="card card-stats">
              <div class="card-header" data-background-color="rose">
                  <i class="mdi mdi-scale-balance"></i>
              </div>
              <div class="card-content">
                  <p class="category">Balance</p>
                  <h4 class="card-title text-info">{{ bal|formatMoney }}</h4>
              </div>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
import filters from '@/filters'
import _ from 'lodash'
import { mapState } from 'vuex'
  export default{
    name:"dash",
    data:function () {
        return{

        }
    },
    filters,
    methods:{
        next:function () {
            let vm =this;
            let payload = {
                year: vm.nav.current.year,
                month: _.isNil(vm.nav.current.month)? null : (parseInt(vm.nav.current.month)+1).toString()
            };
            vm.$store.dispatch('updateNav',payload);
        },
        previous:function () {
            let vm = this;
            let payload = {
                year: vm.nav.current.year,
                month: _.isNil(vm.nav.current.month)? null : (parseInt(vm.nav.current.month)-1).toString()
            };
            vm.$store.dispatch('updateNav',payload);
        }
    },
    computed:{
        ...mapState({
            nav: state => state.nav,
            exp: state => state.totals.expenses,
            inc: state => state.totals.incomes,
            bal: state => state.totals.balance,
        })
    },
    mounted:function(){
        let vm =this;
        vm.$store.dispatch('updateNav',{
            year:null,
            month:null,
            day:null
        });
    }
  }
</script>
