import nav from './nav'
export default {
    updateNav(context,payload) {
        payload = nav(payload.year,payload.month,payload.day)
        context.commit('SETNAV',{...payload,page:"expenses"});
    },
    updateExp(context,payload) {
        context.commit('SETEXP',payload);
    },
    selectExp(context,payload){
        context.commit('SELECTEDEXPENSE',payload);
    }

}
