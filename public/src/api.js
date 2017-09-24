import _ from 'lodash'
export default {
    expenses:function (year,month,day) {
        return getUrl('expenses',year,month,day);
    },
    incomes:function (year,month,day) {
        return getUrl('incomes',year,month,day);
    },
    totals:function (year,month,day) {
        return getUrl('totals',year,month,day);
    },
    tagData:function (id,year,month,day,detail = false) {
      let url = `tags/expenses/${id}`;
      if (detail) {
        return getUrl(url,year,month,day)+"?detail=true";
      }
      return getUrl(url,year,month,day);
    },
    tags:"/api/tags",
    "tag": function (id) {
      return `/api/tag/${id}`;
    },
    expense:function(id = null) {
      if (id) {
        return `/api/expense/${id}`
      }
      return `/api/expense`
    },
    income:function(id = null) {
      if (id) {
        return `/api/income/${id}`
      }
      return `/api/income`
    },
    location:function (query,ll='-31.95,115.86') {
      return `/api/places?ll=${ll}&query=${query}`;
    },
    import_exp:"/api/import/expenses"
}
function getUrl(page,year,month,day){
    let url = "";
    month = (month) ? parseInt(month): null;
    year = (year) ? parseInt(year): null;

    if (!_.isNil(year) && _.isNil(month) && _.isNil(day)) {
        if(month > 12){
            month=1;
            year +=1;
        }
        if(month < 1){
            month=12;
            year -=1;
        }
        url = `/api/${page}/${year}/${month}/${day}`;
    }else if (!_.isNil(year) && !_.isNil(month)){
        if(month > 12){
            month=1;
            year +=1;
        }
        if(month < 1){
            month=12;
            year -=1;
        }
        url = `/api/${page}/${year}/${month}`;
    }else if (!_.isNil(year)) {
        url = `/api/${page}/${year}`;
    }else {
        let d = new Date();
        url = `/api/${page}/${d.getFullYear()}/${d.getMonth()+1}`;
    }
    return url;
}
