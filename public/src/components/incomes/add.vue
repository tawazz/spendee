<template lang="html">
  <modal title="Add Income" :isOpen="show" :cancel="cancel">
    <form name="addForm" id="addForm" method="post" action="/incomes/add">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-shopping"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(income.name == '')}">
              <label class="control-label">Income</label>
              <input type="text" class="form-control" name="name" v-model="income.name">
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-currency-usd"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(income.cost == '')}">
              <label class="control-label">Amount</label>
              <input type="text" class="form-control money" v-model="income.cost" name="cost" @blur="updateCost">
            </div>
          </div>
        </div>
      </div>
      <div class="input-group">
        <span class="input-group-addon">
          <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-calendar"></i></button>
        </span>
        <div class="form-group label-floating" :class="{'is-empty':(income.date == '')}">
          <label class="control-label">Date</label>
          <input type="text" class="form-control" name="date" ref="datepicker">
        </div>
      </div>
        <div class="input-group">
          <span class="input-group-addon" style="padding-top:30px;">
            <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-tag-multiple"></i></button>
          </span>
          <label for="tags" class="control-label" >Tags</label>
          <multiselect class="" v-model="selectedTags" :options="tags" :multiple="true" track-by="id" :searchable="true" :custom-label="searchTags" :hide-selected="true"  placeholder="Select Tag">
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
      <div slot="footer">
          <button type="button" :disabled="busy" class="btn btn-info" @click="addIncome">{{okText}}</button>
          <button v-if="isUpdate" :disabled="busy" type="button" class="btn btn-danger" @click="deleteInc">Delete</button>
          <button type="button" class="btn btn-default" @click="cancel">Cancel</button>
      </div>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
import maskMoney from '@/components/helpers/mask-money'
import notify from '@/components/helpers/notify'
import flatpickr from "flatpickr"
import Multiselect from 'vue-multiselect'
import apis from '@/api'
import { mapState } from 'vuex'
import _ from 'lodash'

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
    },
    selected_inc:{
      type:Object
    }
  },
  components:{
    modal,
    'multiselect':Multiselect
  },
  data:function () {
    return{
      income:{
        name:"",
        cost:"",
        date:"",
        tags:[]
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
    },
    selected_inc:function () {
      if (!_.isNil(this.selected_inc)) {
        delete this.selected_inc['income_tags'];
        this.income = this.selected_inc;
        this.datepicker.setDate(this.income.date,true,'Y-m-d');
        this.mapTagsToSelected();
      }

    }
  },
  computed:{
    okText: function () {
      return this.isUpdate ? "Update" : "Save";
    },
    isUpdate: function () {
      return this.income.id;
    },
    ...mapState({
        busy: state => state.busy > 0
    })
  },
  methods:{
    init:function () {
      let vm =this;
      vm.datepicker = flatpickr(vm.$refs.datepicker, {
        altInput: true,
        altFormat:"D, F j, Y",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr, instance) {
          vm.income.date = dateStr;
        }
      });
      $('.money').maskMoney();
      setTimeout(()=>{
        vm.mapTagsToSelected();
      },100);
    },
    updateCost(e){
      let vm = this;
      vm.income.cost = e.target.value;
    },
    selectTag(value){
      let vm = this;
      vm.income.tags = [];
      $.each(value,(i,tag)=>{
        vm.income.tags.push(tag.id);
      });
    },
    mapTagsToSelected(){
      let vm =this;
      if (vm.income.tags) {
        $.each(vm.income.tags,(i,tag) => {
          vm.tags.map( t => {
            if (t.id == tag) {
              vm.selectedTags.push(t);
            }
          });
        });
      }
    },
    addIncome:function (e) {
      let vm =this;
      vm.$store.dispatch('loading');
      let data = {...vm.income};
      if (vm.income.id) {
        vm.$http.put(apis.income(vm.income.id),data).then((response)=>{
          notify.alert("Income Updated...");
          vm.resetInc();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Updating Income',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      } else {
        vm.$http.post(apis.income(),data).then((response)=>{
          notify.alert("Income Saved...");
          vm.resetInc();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Saving Income',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      }
    },
    cancel:function () {
      this.resetInc();
      this.close();
    },
    resetInc:function () {
      this.income = {
        name:"",
        cost:"",
        date:"",
        tags:[],
        location:{
          name:"",
          lat:"",
          long:""
        },
        repeat:'0',
        repeat_until:null,
        end_repeat:'never',
        reminder:'0'
      };
      this.selectedTags=[],
      document.forms.addForm.reset();
    },
    searchTags({id,name}){
      return name;
    },
    deleteInc(id){
      let vm = this;
      vm.$http.delete(apis.income(vm.income.id)).then((response) => {
        notify.alert("Income Deleted...");
        this.resetInc();
        this.save();
      });
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
<style>
  .multiselect__option--highlight,
  .multiselect__option--highlight:after{
    background: #03a9f4;
  }
  .multiselect--active {
    z-index: 50;
  }
  #addForm > div:nth-child(1) > div:nth-child(2) > div > span > button
  #addForm .btn-fab:disabled,#addForm .btn-fab[disabled][disabled],
  #addForm .btn-fab:disabled:hover,#addForm .btn-fab[disabled][disabled]:hover{
    background-color:#04a00c;
    color: #fff;
    cursor: default;
  }
</style>
