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
          <multiselect v-model="selectedTags" :options="tags" :multiple="true" track-by="id" :searchable="true" :hide-selected="true"  placeholder="Select Tag">
            <template slot="tag" scope="props">
              <ul class="tags">
                <li><a href="#" class="tag" @click.prevent="props.remove(props.option)">{{ props.option.name }}</a></li>
              </ul>
            </template>
            <template slot="option" scope="props">
              {{ props.option.name}}
            </template>
          </multiselect>
        </div>
      </form>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
import maskMoney from '@/components/helpers/mask-money'
import flatpickr from "flatpickr"
import Multiselect from 'vue-multiselect'
import apis from '@/api'

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
        tags:[1,4,12]
      },
      datepicker:null,
      tags:[],
      selectedTags:[]
    }
  },
  watch:{
    selectedTags:function () {
      let vm = this;
      vm.selectTag(vm.selectedTags);
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
      setTimeout(()=>{
        vm.mapTagsToSelected();
      },1000);
    },
    updateCost(e){
      let vm = this;
      setTimeout(function () {
        vm.expense.cost = e.target.value;
      },100)
    },
    selectTag(value){
      let vm = this;
      vm.expense.tags = [];
      $.each(value,(i,tag)=>{
        vm.expense.tags.push(tag.id);
      });
    },
    mapTagsToSelected(){
      let vm =this;
      if (vm.expense.tags) {
        $.each(vm.expense.tags,(i,tag) => {
          vm.tags.map( t => {
            if (t.id == tag) {
              vm.selectedTags.push(t);
            }
          });
        });
      }
    }
  },
  beforeMount:function () {
    let vm =this;
    vm.$http.get(apis.tags).then((response) => {
      vm.tags = response.data;
    })
  },
  mounted:function () {
    let vm = this;
    vm.init();
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style >
.tags {
  list-style: none;
  margin: 0;
  overflow: hidden;
  padding: 0;
}

.tags li {
  float: right;
}

.tag {
  background: #eee;
  border-radius: 3px 0 0 3px;
  color: #999;
  display: inline-block;
  height: 26px;
  line-height: 26px;
  padding: 0 20px 0 23px;
  position: relative;
  margin: 0 10px 10px 0;
  text-decoration: none;
  -webkit-transition: color 0.2s;
}

.tag::before {
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  content: '';
  height: 6px;
  left: 10px;
  position: absolute;
  width: 6px;
  top: 10px;
}

.tag::after {
  background: #fff;
  border-bottom: 13px solid transparent;
  border-right: 10px solid #eee;
  border-top: 13px solid transparent;
  content: '';
  position: absolute;
  left: 0;
  top: 0;
}

.tag:hover {
  background-color: #ef5350;
  color: white;
}

.tags a:hover{
  text-decoration: none;
}

.tag:hover::after {
  border-right-color: #ef5350;
}
</style>
