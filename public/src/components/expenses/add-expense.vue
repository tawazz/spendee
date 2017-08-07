<template lang="html">
  <modal title="Add Expense" :isOpen="show" :ok="save" :cancel="close" okText="Save">
    <form name="addForm" id="addForm" method="post" action="/expenses/add">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <div class="form-group">
              <input type="text" class="form-control" name="name" placeholder="Enter Expense" v-model="expense.name">
          </div>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div class="form-group">
              <input type="text" class="form-control money" name="cost" @blur="updateCost"  placeholder="Enter Amount">
          </div>
        </div>
      </div>
        <div class="form-group">
            <input type="text" class="form-control" name="date" placeholder="Date" ref="datepicker">
        </div>
        <div class="form-group">
          <label for="tags">Tags</label>
          <multiselect v-model="expense.tags" :options="tags" :multiple="true"></multiselect>
        </div>
      </form>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
import maskMoney from '@/components/helpers/mask-money'
import flatpickr from "flatpickr"
import Multiselect from 'vue-multiselect'

export default {
  props:{
    show:{
      type:Boolean,
      default:false
    },
    save:{
        type:Function,
        required:true
    },
    close:{
      type:Function,
      required:true
    }
  },
  components:{
    modal,
    'multiselect':Multiselect
  },
  data:function () {
    return{
      expense:{
        name:"",
        cost:"",
        date:"",
        tags:[]
      },
      datepicker:null,
      tags:['list', 'of', 'options']
    }
  },
  methods:{
    init:function () {
      let vm =this;
      vm.datepicker = flatpickr(vm.$refs.datepicker, {
        altInput: true,
        altFormat:"D, F j, Y",
        onChange: function(selectedDates, dateStr, instance) {
          vm.expense.date = dateStr;
        }
      });
      $('.money').maskMoney();
    },
    updateCost(e){
      let vm = this;
      setTimeout(function () {
        vm.expense.cost = e.target.value;
      },100)
    }
  },
  mounted:function () {
    let vm = this;
    vm.init();
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
