<template lang="html">
  <modal title="Add Expense" :isOpen="show" :cancel="cancelExp">
    <form name="addForm" id="addForm" method="post" action="/expenses/add">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-shopping"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(expense.name == '')}">
              <label class="control-label">Expense</label>
              <input type="text" class="form-control" name="name" v-model="expense.name">
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-currency-usd"></i></button>
            </span>
            <div class="form-group label-floating" :class="{'is-empty':(expense.cost == '')}">
              <label class="control-label">Amount</label>
              <input type="text" class="form-control money" v-model="expense.cost" name="cost" @blur="updateCost">
            </div>
          </div>
        </div>
      </div>
      <div class="input-group">
        <span class="input-group-addon">
          <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-calendar"></i></button>
        </span>
        <div class="form-group label-floating" :class="{'is-empty':(expense.date == '')}">
          <label class="control-label">Date</label>
          <input type="text" class="form-control" name="date" ref="datepicker">
        </div>
      </div>
        <div class="row">
          <div class="col-sm-6 col-xs-12">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-update"></i></button>
              </span>
              <div class="form-group label-floating" :class="{'is-empty':(expense.repeat == '')}">
                <label class="control-label">Repeat</label>
                <select class="form-control" name="repeat" v-model="expense.repeat">
                  <option value="0">Never</option>
                  <option value="1">Daily</option>
                  <option value="7">Weekly</option>
                  <option value="14">Fortnightly</option>
                  <option value="30">Monthly</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12" v-show="expense.repeat!='0'">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-cancel"></i></button>
              </span>
              <div class="form-group label-floating" :class="{'is-empty':(expense.end_repeat == '')}">
                <label class="control-label">End Repeat</label>
                <select class="form-control" name="end_repeat" v-model="expense.end_repeat">
                  <option value="never">Never</option>
                  <option value="date">Date</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-sm-12" v-show="expense.repeat!='0' && expense.end_repeat=='date'">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-calendar-check"></i></button>
              </span>
              <div class="form-group label-floating" :class="{'is-empty':(expense.repeat_until == '')}">
                <label class="control-label">Repeat Until</label>
                <input type="text" class="form-control" name="repeat-until" ref="repeat_until">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12" v-show="expense.repeat!='0'">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-bell-ring"></i></button>
              </span>
              <div class="form-group label-floating" :class="{'is-empty':(expense.reminder == '')}">
                <label class="control-label">Reminder</label>
                <select class="form-control" name="reminder" v-model="expense.reminder">
                  <option value="0">Never</option>
                  <option value="-1">Day Before</option>
                  <option value="-7">Week Before</option>
                  <option value="-30">Month Before</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-xs-12">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-map-marker"></i></button>
              </span>
              <div class="form-group label-floating" :class="{'is-empty':(expense.location.name == '')}">
                <label class="control-label">Location</label>
                <autocomplete class="form-control" v-model="expense.location.name" name="location" :list="locationsDataList" :onInput="selectLocation" :onSelect="updateLocation"/>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="input-group">
              <span class="input-group-addon">
                <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-image"></i></button>
              </span>
              <div class="form-group is-fileinput is-empty">
                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                <input type="file" class="form-control" name="image">
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
          <button type="button" :disabled="busy" class="btn btn-info" @click="addExpense">{{okText}}</button>
          <button v-if="isUpdate" :disabled="busy" type="button" class="btn btn-danger" @click="deleteExp">Delete</button>
          <button type="button" class="btn btn-default" @click="cancelExp">Cancel</button>
      </div>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
import maskMoney from '@/components/helpers/mask-money'
import autocomplete from '@/components/helpers/autocomplete'
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
    selected_exp:{
      type:Object
    }
  },
  components:{
    modal,
    'multiselect':Multiselect,
    autocomplete
  },
  data:function () {
    return{
      expense:{
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
      },
      datepicker:null,
      repeatpicker:null,
      tags:[],
      selectedTags:[],
      locations:[],
      locationsDataList:[],
    }
  },
  watch:{
    selectedTags:function () {
      let vm = this;
      vm.selectTag(vm.selectedTags);
    },
    selected_exp:function () {
      if (!_.isNil(this.selected_exp)) {
        delete this.selected_exp['expense_tags'];
        this.expense = this.selected_exp;
        this.datepicker.setDate(this.expense.date,true,'Y-m-d');
        if (!_.isNil(this.expense.repeat_until)) {
          this.repeatpicker.setDate(this.expense.repeat_until,true,'Y-m-d');
        }
        this.mapTagsToSelected();
      }

    }
  },
  computed:{
    okText: function () {
      return this.isUpdate ? "Update" : "Save";
    },
    isUpdate: function () {
      return this.expense.id;
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
          vm.expense.date = dateStr;
        }
      });
      vm.repeatpicker = flatpickr(vm.$refs.repeat_until, {
        altInput: true,
        altFormat:"D, F j, Y",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr, instance) {
          vm.expense.repeat_until = dateStr;
        }
      });
      $('.money').maskMoney();
      setTimeout(()=>{
        vm.mapTagsToSelected();
      },100);
    },
    updateCost(e){
      let vm = this;
      vm.expense.cost = e.target.value;
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
    },
    addExpense:function (e) {
      let vm =this;
      vm.$store.dispatch('loading');
      let data = {...vm.expense};
      if (vm.expense.id) {
        vm.$http.put(apis.expense(vm.expense.id),data).then((response)=>{
          notify.alert("Expense Updated...");
          vm.resetExp();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Updating Expense',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      } else {
        vm.$http.post(apis.expense(),data).then((response)=>{
          notify.alert("Expense Saved...");
          vm.resetExp();
          vm.save();
          vm.$store.dispatch('done');
        }).catch((error)=>{
          notify.alert('Error Saving Expense',JSON.stringify(error.response.data),{
            type:"danger"
          });
          vm.$store.dispatch('done');
        });
      }
    },
    cancelExp:function () {
      this.resetExp();
      this.close();
    },
    resetExp:function () {
      this.expense = {
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
      this.locations=[],
      this.locationsDataList=[]
      document.forms.addForm.reset();
    },
    selectLocation:function (e) {
      let vm = this;
      if (e.target.value != null) {
        let query = e.target.value;
        if (query.length > 2) {
          vm.$http.get(apis.location(query)).then((response)=>{
            vm.locations=[];
            vm.locationsDataList = [];
            let list = []
            response.data.map(data =>{
              data.response.map(venue =>{
                let address = venue.location.address ? venue.location.address :"";
                let city = venue.location.city ? venue.location.city: "";
                let place = `${venue.name} ${address} ${city}`.trim();
                list.push(place);
                vm.locations.push({name:place,venue});
              });
            });
            vm.locationsDataList = list;
          }).catch((error)=>{
            console.log(error);
          });
        }
        vm.updateLocation(e);
      }
    },
    updateLocation:function (e) {
      let vm = this;
      let name = e.target.value;
      let found = false;
      vm.locations.map(loc => {
        if (loc.name == name ) {
          found = true;
          vm.expense.location.name = name;
          vm.expense.location.lat = loc.venue.location.lat;
          vm.expense.location.long = loc.venue.location.lng;
        }
      });
      if (!found) {
        vm.expense.location.name = name.trim();
        vm.expense.location.lat = "";
        vm.expense.location.long = "";
      }
    },
    searchTags({id,name}){
      return name;
    },
    deleteExp(id){
      let vm = this;
      vm.$http.delete(apis.expense(vm.expense.id)).then((response) => {
        notify.alert("Expense Deleted...");
        this.resetExp();
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
    background-color: #f44336;
    color: #fff;
    cursor: default;
  }
</style>
