export default{
    SETNAV(state, nav){
        state.nav = nav;
    },
    SETPAGE(state,page){
        state.nav.page = page;
    },
    SETEXP(state,payload){
        state.expenses = payload;
    },
    SETINC(state,payload){
        state.incomes = payload;
    },
    SELECTEDEXPENSE(state,payload){
        state.selectedExpense = payload;
    },
    SETTOTALS(state,payload){
        state.totals = payload;
    },
    SETTAGDATA(state,payload){
      state.tagData = payload;
    },
    LOADING(state){
      state.busy += 1;
    },
    DONE(state){
      state.busy -= 1;
    }
}
