export default {
    expenses:function (year,month,day) {
        let url = "";
        if (year && month && day) {
            url = `/api/expenses/${year}/${month}/${day}`;
        }else if (year && month){
            url = `/api/expenses/${year}/${month}`;
        }else if (year) {
            url = `/api/expenses/${year}`;
        }else {
            let d = new Date();
            url = `/api/expenses/${d.getFullYear()}/${d.getMonth()+1}`;
        }
        return url;
    }
}
