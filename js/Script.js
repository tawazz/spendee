$(document).ready(function () {
    $('#addItem').click(function () {
        $('#addExp').modal('show');
    });

    $('#save').click(function () {

        var itmName = document.addForm.name.value;
        var amount = document.addForm.cost.value;
        var date = document.addForm.date.value;
        if (itmName.length > 0 & date.length > 0 & parseFloat(amount) > 0.0) {
            document.getElementById("addForm").submit();
        } else {
          comfirmBox({
            icon:"<i class='fa fa-exclamation-triangle fa-2x text-warning' aria-hidden='true'></i>",
            message:"Fill in all fields",
          });
        }

    });
    $('#saveBudget').click(function () {

        var itmName = document.addForm.name.value;
        var amount = document.addForm.amount.value;
        var date = document.addForm.date.value;
        if (itmName.length > 0  & parseFloat(amount) > 0.0) {
            document.getElementById("addForm").submit();
        } else {
          comfirmBox({
            icon:"<i class='fa fa-exclamation-triangle fa-2x text-warning' aria-hidden='true'></i>",
            message:"Fill in all fields",
          });
        }

    });
    $('#saveInc').click(function () {

        var itmName = document.addForm.name.value;
        var amount = document.addForm.cost.value;
        var date = document.addForm.date.value;
        if (itmName.length > 0 & date.length > 0 & parseFloat(amount) > 0.0) {
            document.getElementById("addForm").submit();
        } else {
          comfirmBox({
            icon:"<i class='fa fa-exclamation-triangle fa-2x text-warning' aria-hidden='true'></i>",
            message:"Fill in all fields",
          });
        }

    });

});

$(function($){
  var cfgCulture = 'en-AU';
  $.preferCulture(cfgCulture);
  $('.money').maskMoney();
});

$(function () {
  $('.datepicker').datetimepicker({ //end time picker
    pickTime: false,
    format: 'YYYY/MM/DD',
    disableTouchKeyboard:true,
    immediateUpdates:true,
    todayHighlight:true
  });
});
function getMonth() {
    var D = new Date();
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    return months[D.getMonth()];
}

function getWholeDate(ds)
{
    var D = new Date(ds);
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var month = D.getMonth();
    var day = D.getDate();
    var year = D.getFullYear();
    var wd = D.getDay();
    var weekDays = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

    return (weekDays[wd] +" " +day + "/" + months[month] + "/" + year);
}

var xmlHttp = createXmlHttpRequestObject();

function addItem(){
    var itmName = document.addForm.itmName.value;
    var amount = document.addForm.amount.value;
    var date = document.addForm.date.value;
    var type = document.addForm.type.value;
    if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){
        xmlHttp.open("GET","../php/addexp.php?name="+itmName+"&amount="+amount+"&date="+date+"&type="+type,true);
        xmlHttp.onreadystatechange = handleServerResponse;
        xmlHttp.send(null);
    }else{
        setTimeout('addItem()', 1000);
    }
}
function createXmlHttpRequestObject(){

    var xmlHttp;
    if(window.ActiveXObject){
        try{
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e){
        xmlHttp = false;
        }
    }else{
        try{
            xmlHttp = new XMLHttpRequest();
        }catch(e){
        xmlHttp = false;
        }
    }

    if(!xmlHttp){
        alert("xmlhttp request failed");
    }else{
    return xmlHttp;
    }

}
function process(){
document.reg.username.style.border = "2px solid white";
document.getElementById("error").innerHTML = "";
    if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){
        var username = encodeURIComponent(document.reg.username.value);
        xmlHttp.open("GET","../php/username.php?username="+username,true);
        xmlHttp.onreadystatechange = handleServerResponse;
        xmlHttp.send(null);
    }else{
        setTimeout('process()', 1000);
    }
}

function handleServerResponse(){
    if(xmlHttp.readyState == 4){
        if(xmlHttp.status == 200){
            message = xmlHttp.responseText;
            //alert(message);
        }else{
        //alert('something went wrong'+xmlHttp.status);
        }
    }
}

function checkLogin(){
    var name = document.login.username.value;
    var pswd = document.login.password.value;
    if(name.length > 0 && pswd.length>0){
        return true;
    }
    else{
        document.getElementById("error").innerHTML = "fill in all details";
        return false;
    }
    return false;
}

function checkReg()
{
    var firstname = document.reg.firstname.value;
    var lastname = document.reg.lastname.value;
    var username = document.reg.username.value;
    var pswd = document.reg.password.value;
    var pswd1 = document.reg.password2.value;
    var email = document.reg.email.value;
    document.reg.email.style.border = "2px solid white";
    document.reg.username.style.border = "2px solid white";
    document.reg.password.style.border = "2px solid white";
    document.reg.password2.style.border = "2px solid white";
    if(firstname.length>0 && lastname.length>0 && username.length>0 && pswd.length>0 && pswd1.length>0 && email.length>0 ){
        if(pswd == pswd1 && pswd.length >5){
            if(email.indexOf("@") > -1 && email.indexOf(".com") > -1)
            {
                return true;
            }
            else{
                document.reg.email.style.border = "1px solid red";
                document.getElementById("error").innerHTML = "enter a valid email";
                return false;
            }
        }else{
            document.reg.password.style.border = "1px solid red";
            document.reg.password2.style.border = "1px solid red";
            if(pswd.length < 6){
                document.getElementById("error").innerHTML = "password must be six or more characters";
            }else{
                document.getElementById("error").innerHTML = "password dont match";
            }

            return false;
        }
    }else{
        document.getElementById("error").innerHTML = "fill in all details";
        return false;
    }

}

function isNotEmpty(field){
    var inputStr = field.value;
    field.style.border="1px solid #ccc";
	if (inputStr == "" || inputStr == null ) {
		field.style.border="2px solid #F16C63";
		field.focus();
		field.select();
		return false;
	}
	return true;
}

function isNumber(field){
	if (isNotEmpty(field)){
	    var inputStr = field.value;
        field.style.border="1px solid #ccc";
		for (var i = 0; i < inputStr.length; i++){
		    var oneChar = inputStr.substring(i, i + 1);
			if (oneChar < "0" || oneChar > "9"){
			    field.style.border="1px solid #F16C63";
				field.focus();
				field.select();
				return false;
			}
		}
        if(field.value.length < 9){
            field.style.border="1px solid #a94442";
			field.focus();
			field.select();
            return false;
        }
		return true;
	}
	return false;
}


function validate(form) {
    if(isNotEmpty(form.name) && isNotEmpty(form.phone) && isNotEmpty(form.email)  && isNotEmpty(form.msg) ){
	return true;
    }else{
        return false;
    }
}

Number.prototype.formatMoney = function(c, d, t){
var n = this,
  c = isNaN(c = Math.abs(c)) ? 2 : c,
  d = d == undefined ? "." : d,
  t = t == undefined ? "," : t,
  s = n < 0 ? "-" : "",
  i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
  j = (j = i.length) > 3 ? j % 3 : 0;
 return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
