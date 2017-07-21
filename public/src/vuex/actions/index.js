import nav from './nav'
export default {
    updateNav({commit,state},payload) {
        var page = (payload.page)?payload.page:(state.nav.page)?state.nav.page:"expenses";
        payload = nav(payload.year,payload.month,payload.day);
        commit('SETNAV',{...payload,page});
    },
    updateExp(context,payload) {
        context.commit('SETEXP',payload);
    },
    updatePage(context,payload) {
        context.commit('SETPAGE',payload);
    },
    updateInc(context,payload) {
        context.commit('SETINC',payload);
    },
    selectExp(context,payload){
        context.commit('SELECTEDEXPENSE',payload);
    }

}
