//Tawanda Nyakudjga
//created 27/05/2013
//last changed 11/25/2013
// project Budget
// user interface scripts

//global variables
var D = new Date();
var Year = D.getFullYear();
var s = 1;
var d = 1;

$(document).ready(function () {


    $(document).on("click", ".bud", function () {
        budToggle();
    });

    $(".close").click(function () {
        $(".pop").hide();
        $("#expform").hide();
        $("#incform").hide();
    });
  $(document).keyup(function(e) {
    if (e.keyCode == 27) {// esc
        $(".pop").hide();
        $("#expform").hide();
        $("#incform").hide();
    }   
  });

    $("#addExp").click(function () {
        ExpForm();
    });

    $("#addInc").click(function () {
        IncForm();
    });
    
    // change date values s1 -> today s2-> yesterday 
    $('#up').click(function () {

        for (var i = 1; i < 8; i++) {
            $("#s" + i).hide();
        }
        if (s == 1) {
            $("#s7").show();
            s = 7;
        } else {
            $('#s' + (s - 1)).show();
            s -= 1;
        }
    });

    $('#down').click(function () {

        for (var i = 1; i < 8; i++) {
            $("#s" + i).hide();
        }
        if (s == 7) {
            $("#s1").show();
            s = 1;
        } else {
            $('#s' + (s + 1)).show();
            s += 1;
        }
    });

    $('#incup').click(function () {
        for (var i = 1; i < 8; i++) {
            $("#d" + i).hide();
        }
        if (d == 1) {
            $("#d7").show();
            d = 7;
        } else {
            $('#d' + (d - 1)).show();
            d -= 1;
        }
    });

    $('#incdown').click(function () {

        for (var i = 1; i < 8; i++) {
            $("#d" + i).hide();
        }
        if (d == 7) {
            $("#d1").show();
            d = 1;
        } else {
            $('#d' + (d + 1)).show();
            d += 1;
        }
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

function ExpForm(){
    $("#expform").show();
     $(".pop").show();
}

function IncForm(){
    $("#incform").show();
     $(".pop").show();
}




