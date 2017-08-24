export default function(year,month,day){
    let nav = {};
    if(year && month && day ){
        month = parseInt(month);
        year = parseInt(year)
        if(month == 13){
            month=1;
            year +=1;
        }
        if(month == 0){
            month=12;
            year -=1;
        }

        var date = new Date(`${year}-${month}-${day}`);
        nav['date']= date;
        date = moment(date).format('d/MMM/YYYY');
        nav['display'] = date;
        nav['next'] = `${year}/${month}/${day+1}`;
        nav['prev'] = `${year}/${month}/${day-1}`;
        nav['current']={'year':year,'month':month,'day':day};

    }else if(year && month){
        month = parseInt(month);
        year = parseInt(year)

        if(month == 13){
            month=1;
            year +=1;
        }
        if(month == 0){
            month=12;
            year -=1;
        }

        var date = `${year}/${month}/1`;
        nav['date']= date;
        var formatted_date = moment(date,'YYYY/M/dddd').format('MMM/YYYY');
        nav['display'] = formatted_date;
        nav['next'] = moment(date,'YYYY/M/dddd').add(1, 'months').format('YYYY/M');
        nav['prev'] = moment(date,'YYYY/M/dddd').subtract(1, 'months').format('YYYY/M');
        nav['current']={'year':year,'month':month,'day':1};

    }else if(year){

        var date = new Date(`${year}-1-1`);
        nav['date']= date;
        var formatted_date = moment(date).format('YYYY');
        nav['display'] = formatted_date;
        nav['next'] = moment(date).add(1, 'y').format('YYYY');
        nav['prev'] = moment(date).subtract(1, 'y').format('YYYY');
        nav['current']={'year':year,'month':1,'day':1};

    }else{

        date = new Date();
        nav['date']= date;
        year = date.getFullYear(),
        month =date.getMonth()+1,
        day = 1,
        date = `${year}/${month}/1`;
        var formatted_date= moment(date,'YYYY/M/dddd').format('MMM/YYYY');
        nav['display'] = formatted_date;
        nav['next'] = moment(date,'YYYY/M/dddd').add(1, 'months').format('YYYY/M');
        nav['prev'] = moment(date,'YYYY/M/dddd').subtract(1, 'months').format('YYYY/M');
        nav['current']={'year':year,'month':month,'day':1};

    }

    return nav;
}
