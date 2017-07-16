export default{
    SETNAV(state, nav) {
        state.nav = nav;
    },
    SETEXP(state,payload){
        state.expenses = payload;
    },
    SELECTEDEXPENSE(state,payload){
        state.selectedExpense = payload;
    }
}
