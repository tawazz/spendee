import nav from './nav'
export default {
    updateNav(context,payload) {
        console.log(payload);
        context.commit('SETNAV',nav(payload.year,payload.month,payload.day));
    }
}
