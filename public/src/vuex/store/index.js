import Vuex from 'vuex'
import Vue from 'vue'
import nav from './nav'
import mutations from '../mutations'
import actions from '../actions'
import getters from '../getters'

Vue.use(Vuex)

var store = new Vuex.Store({
    state: {
        nav,
    },
    mutations,
    actions,
    getters:{
        /*showAlert: state => {
            return state.alert.visible;
        },*/

    }
});

export default store;
