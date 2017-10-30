/**
* confirmBox components
* author: Tawanda Nyakudjga
* date: 9/10/2016
* alertOptions:{
    icon:"<i class='fa fa-exclamation-triangle fa-2x text-danger' aria-hidden='true'></i>",
    message:"Are you sure you want to Delete!!!" ,
    buttons:[
        {
          text:"Delete",
          event: "delete",
          bsColor:"btn-danger",
          handler:function(e) {

             vm.showAlert();
          },
          autoclose:true
        }
    ]
    }
**/
<template lang="html" id="confirmbox">
    <div :id="confirmModal" class="modal fade">
      <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <!-- dialog body -->
          <div class="modal-body">
          <div class="row">
              <div :id="icon" class="col-sm-12 text-center" style="font-size:75px; ">
                  <!--icon goes here-->
              </div>
              <div class="col-sm-12 text-center">
                  <p :id="text"><!--modal text--></p>
              </div>
          </div>
          </div>
          <!-- dialog buttons -->
          <div  class="modal-footer">
              <div class="row">
                  <div class="col-lg-12" :id="buttons">
                      <!--buttons-->
                  </div>
              </div>
          </div>
      </div>
      </div>
  </div>
</template>

<script>
import {$} from '../../hooks.js'
import {bus} from './eventBus.js'

var confirmModal = module.exports = {
    data:function () {
        return {
            confirmModal: 'confirmModal'+this._uid,
            icon: 'modalIcon'+this._uid,
            text: 'modalText'+this._uid,
            buttons: 'modalButtons'+this._uid,
            eventHandler: Array(),
        }
    },
    props:{
        options:{
            required:true,
            type:Object
        },
        id:{
            required:true
        }
    },
    methods:{
        confirmBox:function(json){
            let vm = this;
            var Obj = json;
            var confirmModal = $("#"+vm.confirmModal);
            var icon = $("#"+vm.icon);
            var text = $("#"+vm.text);
            var buttons = ("#"+vm.buttons);
            var passed_id = Obj.id;
            var autoclose =(typeof Obj.autoclose != "undefined")? Obj.autoclose: true;
            $(icon).html(Obj.icon);
            $(text).html(Obj.message);
            $(buttons).html("");
            if (typeof Obj.buttons != "undefined")
            {
               $.each(Obj.buttons, function (i, btn)
               {
                   var eventHandler = (typeof btn.eventHandler != "undefined") ? btn.eventHandler : "@click";
                   $(buttons).append("<button type=\"button\" data-click="+btn.event+" class=\"btn " + btn.bsColor + "\" style='margin-bottom:10px;'>" + btn.text + "</button>");
                   $(function () {
                          if(passed_id === vm.id){
                               $('button[data-click]').on('click',function () {

                                   if ($(this).attr('data-click') ==  btn.event) {
                                      btn.handler();
                                   }
                                   if(autoclose){
                                       $(confirmModal).modal('hide');
                                   }
                               });
                          }
                   })
               });
            }
            $(buttons).append("<button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\" style='margin-bottom:10px;'>Cancel</button>");
        }
   },
   mounted:function () {
       var vm = this;
       vm.confirmBox(this.options);
       bus.$on('showAlert', function(id){
          if(id === vm.id){
              $("#"+vm.confirmModal).modal('show');
          }
      });


   }
}

</script>

<style scoped>
    .modal-body,.modal-footer {
        background-color: #fff;
        color: #333;
    }
    .modal-footer .btn+.btn {
        margin-bottom: 10px;
        margin-left: 5px;
    }
</style>
