import notify from '@/components/helpers/notify'
export default {
  error_handler:function (vm,error) {
    if (error.response.status == 401) {
      document.location.assign('/login');
    }
    notify.alert('Error',JSON.stringify(error.response.data),{
      type:"danger"
    });
  }
}
