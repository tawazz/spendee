<template>
    <div v-show="isOpen" :transition="transition">
        <div class="modal fade in" @click.self="clickMask">
            <div class="modal-dialog" :class="modalClass" role="document">
                <div class="modal-content">
                    <!--Header-->
                    <slot name="header">
                        <div class="modal-header">
                            <a type="button" class="close" @click="cancel">x</a>
                            <h4 class="modal-title">
                                <slot name="title">
                                    {{title}}
                                </slot>
                            </h4>
                        </div>
                    </slot>
                    <!--Container-->
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                    <!--Footer-->
                    <div class="modal-footer">
                        <slot name="footer">
                            <button type="button" :class="okClass" @click="ok">{{okText}}</button>
                            <button type="button" :class="cancelClass" @click="cancel">{{cancelText}}</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop in"></div>
    </div>
</template>

<script>
    /**
     * Bootstrap Style Modal Component for Vue
     * Depend on Bootstrap.css
     */

     export default {
        props: {
            title: {
                type: String,
                default: 'Modal'
            },
            small: {
                type: Boolean,
                default: false
            },
            large: {
                type: Boolean,
                default: false
            },
            full: {
                type: Boolean,
                default: false
            },
            force: {
                type: Boolean,
                default: true
            },
            transition: {
                type: String,
                default: 'modal'
            },
            okText: {
                type: String,
                default: 'OK'
            },
            cancelText: {
                type: String,
                default: 'Cancel'
            },
            okClass: {
                type: String,
                default: 'btn btn-default'
            },
            cancelClass: {
                type: String,
                default: 'btn btn-danger'
            },
            closeWhenOK: {
                type: Boolean,
                default: false
            },
            isOpen: {
              type:Boolean,
              default:false
            },
            ok:{
              type:Function,
              default: function () {
                return void(0);
              }
            },
            cancel:{
              type:Function,
              default: function () {
                return void(0);
              }
            }
        },
        data () {
            return {
                duration: null,
            };
        },
        computed: {
            modalClass () {
                return {
                    'modal-lg': this.large,
                    'modal-sm': this.small,
                    'modal-full': this.full
                }
            }
        },
        beforeDestroy () {
            document.body.className = document.body.className.replace(/\s?modal-open/, '');
        },
        watch: {
            isOpen () {
              let vm = this;
              vm.show(vm.isOpen);
            }
        },
        methods: {
            clickMask () {
                if (!this.force) {
                    this.cancel();
                }
            },
            show(isOpen){
              let vm = this;
              if (isOpen) {
                $("body").addClass("modal-open");
              }else {
                $("body").removeClass("modal-open")
              }

            }
        }
     };
</script>


<style scoped>
.modal {
    display: block;
}
.modal .btn {
    margin-bottom: 0px;
}
.modal-transition {
    transition: all .6s ease;
}
.modal-leave {
    border-radius: 1px !important;
}
.modal-transition .modal-dialog, .modal-transition .modal-backdrop {
    transition: all .5s ease;
}
.modal-enter .modal-dialog, .modal-leave .modal-dialog {
    opacity: 0;
    transform: translateY(-30%);
}
.modal-enter .modal-backdrop, .modal-leave .modal-backdrop {
    opacity: 0;
}
.close {
    color: #d9534f;
    opacity: 1;
}
#okBtn {
    margin-bottom: 0px;
}
</style>
