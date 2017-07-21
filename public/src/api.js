export default {
    expenses:function (year,month,day) {
        return getUrl('expenses',year,month,day);
    },
    incomes:function (year,month,day) {
        return getUrl('incomes',year,month,day);
    }
}
function getUrl(page,year,month,day){
    let url = "";
    month = (month) ? parseInt(month): null;
    year = (year) ? parseInt(year): null;
    if(month > 12){
        month=1;
        year +=1;
    }
    if(month < 1){
        month=12;
        year -=1;
    }
    if (year && month && day) {
        url = `/api/${page}/${year}/${month}/${day}`;
    }else if (year && month){
        url = `/api/${page}/${year}/${month}`;
    }else if (year) {
        url = `/api/${page}/${year}`;
    }else {
        let d = new Date();
        url = `/api/${page}/${d.getFullYear()}/${d.getMonth()+1}`;
    }
    return url;
}
