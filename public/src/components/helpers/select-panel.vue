<template lang="html">
    <div id="select-panel">
        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-6 options">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Features</h3>
                    </div>
                    <div class="panel-body" v-bind:class="{ 'empty-options': allOptionsSelected }">
                        <p v-show="allOptionsSelected">
                             All options selected
                         </p>
                        <ul class="list-group">
                            <a href="" v-show="!isDisabled" v-for="option,key in options"  @click.prevent="addSelected(option,key)" class="list-group-item list-group-item-primary">{{option.name}}</a>
                            <a href="" v-show="isDisabled" v-for="option,key in options"  @click.prevent.stop="" class="list-group-item disabled">{{option.name}}</a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 options">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Selected Features</h3>
                    </div>
                    <div class="panel-body"  v-bind:class="{ 'empty-options': !hasSelectedOptions }">
                        <p v-show="!hasSelectedOptions">
                             No options selected
                         </p>
                        <ul class="list-group">
                            <a href="" v-show="!isDisabled" v-for="option,key in selected"  @click.prevent="removeSelected(option, key)" class="list-group-item ">{{option.name}}</a>
                            <a href="" v-show="isDisabled" v-for="option,key in selected"  @click.prevent.stop="" class="list-group-item disabled">{{option.name}}</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from 'jquery'

export default {
    name:'select-panel',
    data:function () {
        return{
            isDisabled:false
        }
    },
    props:{
        options:{
            type:Array,
            required:true,
            default:function () {
                return [];
            }
        },
        disabled:{
            type:Boolean,
            default:false
        },
        selected:{
            type:Array,
            required:true,
            default:function () {
                return [];
            }
        }
    },
    computed : {
        allOptionsSelected:function () {
            return !this.options.length > 0;
        },
        hasSelectedOptions:function  () {
            return this.selected.length > 0;
        }
    },
    methods:{
        addSelected: function(option, key) {
            let vm = this;
            vm.selected.push(option);
            vm.options.splice(key, 1);
            vm.selected.sort(function(a, b) {
                return parseInt(a.id) - parseInt(b.id)
            });
        },
        removeSelected: function(option, key) {
            let vm = this;
            vm.options.push(option);
            vm.selected.splice(key, 1);
            vm.options.sort(function(a, b) {
                return parseInt(a.id) - parseInt(b.id)
            });
        },
        enabled:function (isEnabled) {
            this.isDisabled = !isEnabled;
        },
        loadSelectedFeatures: function(passed_features) {
            let vm = this;
            $.each(passed_features, function(i, cgfeature) {
                $.each(vm.options, function(j, feat) {
                    if (feat != null) {
                        if (cgfeature.id == feat.id) {
                            vm.options.splice(j, 1);
                            vm.selected.push(cgfeature);
                        }
                    }
                })
            });


        }
    }
}
</script>

<style lang="css">
    .options >.panel>.panel-body{
        padding:0;
        max-height: 300px;
        min-height: 300px;
        overflow: auto;
    }
    .options .list-group{
        margin-bottom: 0;
    }
    .options .list-group-item{
        border-radius: 0;
    }
    .list-group-item:last-child{
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .empty-options{
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 300px;
        color: #ccc;
        font-size: 2em;
    }
</style>
