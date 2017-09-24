<template>
  <div>
      <div v-for="item,date in data" class="card">
          <div class="card-heading" :data-background-color="color">
              <h3 class="text-center">{{ date|date }}</h3>
          </div>
            <div v-for="i in item">
                <div class="card-footer capitalize">
                    <div>
                      <a href="#" :style="{color}" @click.prevent="edit(i.id)" style="bottom:0;padding-left:15px;">{{i.name}}</a>
                        <router-link class="tag" style="margin:0 5px;" :key="i.id+'_'+tag.tags.id" v-for="tag in i.expense_tags":to="{ name: 'Tags',params: { id: tag.tags.id }}">
                          {{ tag.tags.name}}
                        </router-link>
                        <router-link class="tag" style="margin:0 5px;" :key="i.id+'_'+tag.tags.id" v-for="tag in i.income_tags":to="{ name: 'Tags',params: { id: tag.tags.id }}">
                          {{ tag.tags.name}}
                        </router-link>
                      <span v-if="i.is_recurring" class="mdi mdi-reload" style="font-size:1.3em" ></span>
                    </div>
                    <span class="pull-right" :style="{color}">
                      <i class="fa fa-usd">{{ i.cost|formatMoney }}</i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
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
    props:["data","color","type","editCallback"],
    filters,
    methods:{
        total:function (item) {
            return item.reduce((a, b) => {return parseFloat(a) + parseFloat(b.cost); }, 0);
        },
        edit: function (id) {
          this.editCallback(id,this.type);
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
  a.tag{
    bottom:0;
    text-decoration: none;
  }
</style>
