<template lang="html">
  <modal title="Add Budget" :isOpen="show" :cancel="cancel">
    <form name="addForm" id="addForm" method="post">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-shopping"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(budget.name == '')}">
              <label class="control-label">Budget</label>
              <input type="text" class="form-control" name="name" v-model="budget.name">
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-currency-usd"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(budget.amount == '')}">
              <label class="control-label">Amount</label>
              <input type="text" class="form-control money" v-model="budget.amount" name="amount" @blur="updateCost">
            </div>
          </div>
        </div>
      </div>
      <div class="input-group">
        <span class="input-group-addon" style="padding-top:30px;">
          <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-tag-multiple"></i></button>
        </span>
        <label for="tags" class="control-label" >Tags</label>
        <multiselect class="" v-model="selectedTags" :options="tags" :multiple="true" track-by="id" :searchable="true" :custom-label="searchTags" :hide-selected="true"  placeholder="Select Tag">
          <template slot="tag" slot-scope="props">
            <ul class="tags">
              <li><a href="#" class="tag" @click.prevent="props.remove(props.option)">{{ props.option.name }}</a></li>
            </ul>
          </template>
          <template slot="option" slot-scope="props">
            {{ props.option.name}}
          </template>
        </multiselect>
      </div>
      </form>
      <div slot="footer">
          <button type="button" :disabled="busy" class="btn btn-info" @click="addBudget">{{okText}}</button>
          <button v-if="isUpdate" :disabled="busy" type="button" class="btn btn-danger" @click="deleteBudget">Delete</button>
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
    selected_budget:{
      type:Object
    }
  },
  components:{
    modal,
    'multiselect':Multiselect
  },
  data:function () {
    return{
      budget:{
        name:"",
        amount:"",
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
    selected_budget:function () {
      if (!_.isNil(this.selected_budget)) {
        this.budget = this.selected_budget;
        this.mapTagsToSelected();
      }

    }
  },
  computed:{
    okText: function () {
      return this.isUpdate ? "Update" : "Save";
    },
    isUpdate: function () {
      return this.budget.id;
    },
    ...mapState({
        busy: state => state.busy > 0,
        current_period: state => `${state.nav.current.year}-${state.nav.current.month}-1`
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
          vm.budget.date = dateStr;
        }
      });
      $('.money').maskMoney();
      setTimeout(()=>{
        vm.mapTagsToSelected();
      },100);
    },
    updateCost(e){
      let vm = this;
      vm.budget.amount = e.target.value;
    },
    selectTag(value){
      let vm = this;
      vm.budget.tags = [];
      $.each(value,(i,tag)=>{
        vm.budget.tags.push(tag.id);
      });
    },
    mapTagsToSelected(){
      let vm =this;
      if (vm.budget.tags) {
        if(vm.budget.tags.length == 36){
          vm.selectedTags.push(vm.tags[0]);
        }else{
          $.each(vm.budget.tags,(i,budget) => {
            vm.tags.map( t => {
              if (t.id == budget) {
                vm.selectedTags.push(t);
              }
            });
          });
        }
      }
    },
    addBudget:function (e) {
      let vm =this;
      vm.$store.dispatch('loading')
      vm.budget.date = vm.current_period;
      let data = {...vm.budget};
      if (vm.budget.id) {
        vm.$http.put(apis.budget(vm.budget.id),data).then((response)=>{
          notify.alert("Budget Updated...");
          vm.resetBudget();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Updating Budget',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      } else {
        vm.$http.post(apis.budget(),data).then((response)=>{
          notify.alert("Budget Saved...");
          vm.resetBudget();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Saving Budget',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      }
    },
    cancel:function () {
      this.resetBudget();
      this.close();
    },
    resetBudget:function () {
      this.budget = {
        name:"",
        amount:"",
        date:"",
        tags:[]
      };
      this.selectedTags=[],
      document.forms.addForm.reset();
    },
    searchTags({id,name}){
      return name;
    },
    deleteBudget(id){
      let vm = this;
      vm.$http.delete(apis.budget(vm.budget.id)).then((response) => {
        notify.alert("Budget Deleted...");
        this.resetBudget();
        this.save();
      });
    }
  },
  beforeMount:function () {
    let vm =this;
    vm.$http.get(apis.tags).then((response) => {
      vm.tags = [{id:0,name:'All'},...response.data];
    })
  },
  mounted:function () {
    let vm = this;
    vm.init();
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
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
    background-color:#03a9f4;
    color: #fff;
    cursor: default;
  }
</style>
