import nav from './nav'
import { apis, axios } from '@/hooks'

export default {
    updateNav({commit,state},payload) {
        var page = (payload.page)?payload.page:(state.nav.page)?state.nav.page:"expenses";
        payload = nav(payload.year,payload.month,payload.day);
        commit('SETNAV',{...payload,page});
        state.busy += 1;
        axios.get(apis.totals(payload.current.year,payload.current.month,null)).then(res => {
            commit('SETTOTALS',res.data);
            state.busy -= 1;
        });
    },
    setTotals(context,payload) {
      context.commit('SETTOTALS',payload);
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
    },
    updateTagData(context,payload){
      context.commit('SETTAGDATA',payload);
    },
    loading(context){
      context.commit('LOADING');
    },
    done(context){
      context.commit('DONE');
    }

}
