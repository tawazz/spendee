<template lang="html" id="editor">
    <div class="row" style="margin-top: 40px;">
        <div class="col-md-12">
            <div class="form-group">
                <slot name="label">
                    <label class="control-label">Description</label>
                </slot>
                <div :id="editor_id" class="form-control editor"></div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    $,
}
from '../../hooks.js'
import Editor from 'quill';
import Render from 'quill-render';

export default {
    name:"editor",
    data:function () {
        let vm = this;
        return {
            editor:null,
            editor_id:'editor'+vm._uid
        }
    },
    props:['value'],
    methods:{
        disabled: function(is_disabled){
            this.editor.enable(!is_disabled);
        }
    },
    mounted: function(){
        let vm = this;
        vm.editor = new Editor('#'+vm.editor_id, {
            modules: {
                toolbar: true
            },
            theme: 'snow'
        });
        vm.editor.clipboard.dangerouslyPasteHTML(0, vm.value, 'user');
        vm.editor.on('text-change', function(delta, oldDelta, source) {
            var text = $('#'+vm.editor_id+' >.ql-editor').html();
            vm.$emit('input', text);
        });
    }
}
</script>

<style lang="css" scoped>
    .editor{
        height: 200px;
    }
</style>
