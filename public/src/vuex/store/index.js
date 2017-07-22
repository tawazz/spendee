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
        expenses:[],
        selectedExpense:null,
        incomes:[],
        selectedIncome:null,
        totals:{
            balance:0,
            expenses:0,
            incomes:0
        }
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
