<template lang="html">
  <modal :isOpen="show" title="Import Expenses" :ok="uploadFile" :cancel="close" okText="import">
    <form  action="" method="post">
      <div class="row">
        <div class="col-sm-12">
          <div class="input-group">
            <span class="input-group-addon">
              <button disabled class="btn btn-fab-mini btn-info btn-fab"><i class="mdi mdi-file"></i></button>
            </span>
            <div class="form-group is-fileinput is-empty">
              <input type="text" readonly="" class="form-control" placeholder="Browse...">
              <input type="file" class="form-control" name="expenses" ref="csv">
            </div>
          </div>
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
import modal from '@/components/helpers/modal'
import notify from '@/components/helpers/notify'
import { apis,axios } from '@/hooks'
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
    modal
  },
  methods:{
    uploadFile(){
      var vm = this;
      var data = new FormData();
      var file = $(vm.$refs.csv).prop('files');
      if (file.length > 0) {
        file = file[0];
        if (file.type == 'text/csv') {
          vm.$store.dispatch('loading');
          data.append('expenses',file);
          data.append('csrf_name',Window.csrf.name);
          data.append('csrf_value',Window.csrf.value);
          var config = {
            onUploadProgress: function(progressEvent) {
              var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
            }
          };
          var inst = axios.create();;
          inst.post(apis.import_exp,data,config).then((response) =>{
            vm.save();
            vm.$store.dispatch('done');
          }).catch((error) => {
            console.error(error);
            notify.alert('Error! importing file',"",{
              type:"danger"
            });
            vm.$store.dispatch('done');
          });
        } else {
          notify.alert('Error! only csv files supported',"",{
            type:"danger"
          });
          vm.$store.dispatch('done');
        }
      } else {
        notify.alert('Error! please select a file',"",{
          type:"danger"
        });
        vm.$store.dispatch('done');
      }

    }

  }
}
</script>

<style lang="css" scoped>
.btn-fab:disabled,.btn-fab[disabled][disabled],
.btn-fab:disabled:hover,.btn-fab[disabled][disabled]:hover{
  background-color: #f44336;
  color: #fff;
  cursor: default;
}
</style>
