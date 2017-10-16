import $ from 'jquery'
import notify from 'bootstrap-notify'
const defaults = {
   icon:'mdi mdi-bell',
   type:'info',
   duration:3000,
   from: "top",
   align: "right"
}
export default {
  alert : function (title,message="",options = defaults) {
    $.notify({
        title,
        icon:options.icon ? options.icon : defaults.icon,
        message,
      },{
          type:options.type ? options.type : defaults.type,
          timer: options.duration ? options.duration : defaults.duration,
          placement: {
              from: options.from ? options.from : defaults.from,
              align: options.align ? options.align : defaults.align
          },
          offset: {
            x:20,
            y:75
          },
          template:`
          <div data-notify="container"
           class="col-xs-11 col-sm-4 alert alert-{0} animated alert-with-icon fadeInDown"
           role="alert" >
           <button type="button" aria-hidden="true" class="close" data-notify="dismiss">
            <i class="mdi mdi-close"></i>
           </button>
            <i data-notify="icon" class="notifications"></i>
            <span data-notify="title">{1}</span>
            <span data-notify="message">{2}</span>
            <a href="#" target="_blank" data-notify="url"></a>
          </div>
          `
      });
  }
}
