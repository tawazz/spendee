//Tawanda Nyakudjga
//created 27/05/2013
//last changed 18/06/2013
// project Budget
// budget calculations script
// added new method of displaying transactions
//munupulate budgets
//added login functions

//Global attributes

var totalExp=0;
var totalInc = 0;
var expCount = 0;
var incCount = 0;
var budsCount = 0;
var limiter = 100
var allExp = new Array();
var allInc = new Array();
var allBuds = new Array();
var t;
//Main Functions
$(document).ready(function () {
    numOnly("#expAmount");
    numOnly("#incAmount");
    numOnly("#budAmount");
    //php submit buttons
    /*
    Submit("#userLogin");
    Submit("#expSubmit");
    Submit("#addSubmit");
    Submit("#budSubmit");
    */

    $("#expSubmit").click(function () {

        //calExp();
        var name = document.form_exp.expName.value;
        var amnt = document.form_exp.expAmount.value;
        var day = D.getDate();
        var month = D.getMonth();
        var year = D.getFullYear();
        var today = year + "," + (month + 1) + "," + day;
        var yesterday = year + "," + (month + 1) + "," + (day - 1);
        var prevD = year + "," + (month + 1) + "," + (day - (s - 1));
        if (day - (s - 1) < 1) {
            var lastm = new Date(year, month, (day - (s - 1)));
            var lastD = lastm.getDate();
            var prevD = year + "," + (month) + "," + lastD;
            if (month == 0) {
                prevD = year - 1 + "," + 12 + "," + lastD;
            }
        }

        if (s == 1) {
            $("#dateval").html("<input type='hidden' name='date' value='" + today + "'/>");
        }

        if (s > 1 && s < 8) {

            $("#dateval").html("<input type='hidden' name='date' value='" + prevD + "'/>");
        }
        if (name.length > 0 && amnt.length > 0) {
            Submit("form_exp");
        } else {
            alert("enter all form details");
        }
    });

    $("#addSubmit").click(function () {

        //calInc();
        var name = document.form_inc.incName.value;
        var amnt = document.form_inc.incAmount.value;
        var day = D.getDate();
        var month = D.getMonth();
        var year = D.getFullYear();
        var today = year + "," + (month + 1) + "," + day;
        var yesterday = year + "," + (month + 1) + "," + (day - 1);
        var prevD = year + "," + (month + 1) + "," + (day - (d - 1));
        if (day - (d - 1) < 1) {
            var lastm = new Date(year, month, (day - (d - 1)));
            var lastD = lastm.getDate();
            var prevD = year + "," + (month) + "," + lastD;
            if (month == 0) {
                prevD = year - 1 + "," + 12 + "," + lastD;
            }
        }

        if (d == 1) {
            $("#incdate").html("<input type='hidden' name='date' value='" + today + "'/>");
        }
        if (d > 1 && d < 8) {

            $("#incdate").html("<input type='hidden' name='date' value='" + prevD + "'/>");
        }
        if (name.length > 0 && amnt.length > 0) {
            Submit("form_inc");
        } else {
            alert("enter all details");
        }
    });

    $(document).on("click", "#LogIn", function () {
        //userLogin();
        self.location = "index.php";
    });
});
//End Main

//Draws sum up graphs
function drawGraph(area,total,color)
{
    var bal = (totalInc - totalExp);
    bal = Math.round(bal * 100) / 100;

     if (total < limiter - 20) {
            $(area).html("<div style=\"width:" + (total / limiter) * 100 + "%; background-color:"+ color +";color:#f5f5f5; height: 50px;border-radius:5px; line-height:3; \">" + total + "</div>");
        }
        else {
            limiter = limiter * 10;
            $(area).html("<div style=\"width:" + (total / limiter) * 100 + "%; background-color: "+color +" ;color:#f5f5f5; height: 50px;border-radius:5px; \">" + total + "</div>");
        }
        if (totalExp >= totalInc) {
            $("#bal").html("<div style=\"width:" + 1 + "%; background-color: #808080; color:red;line-height:3; height: 50px;border-radius:5px; \">" + bal+ "</div>");
        } else {
            $("#bal").html("<div style=\"width:" + ((totalInc - totalExp) / totalInc) * 100 + "%; background-color: #808080;color:#f5f5f5; height: 50px;border-radius:5px;line-height:3; \">" + bal + "</div>");
        }
}
//prevent text input to numbers only
function numOnly(textbox)
{
    $(textbox).keypress(function (e) {
        var a = [];
        var k = e.which;

        for (i = 48; i < 58; i++)
            a.push(i);

        a.push(0);
        a.push(8);
        a.push(46);

        if (!(a.indexOf(k) >= 0)) {
            e.preventDefault();
            alert("Enter numbers only");
        }
    });
}
//adds budget for  select budfor entries
function budgetFor()
{
    if (expCount > 0) {
        $("#budFor").html("<select id=\"budFor\"><option value=\"allExp\">All Expenses</option>")
        for (var i = 0; i < expCount; i++) {
            $("#budFor").append("<option value=" + allExp[i].name + ">" + allExp[i].name + "</option>");
        }
        $("#budFor").append("</select> <br/>");
    }
    else {
        $("#budFor").html("<select id=\"budFor\"><option value=\"allExp\">All Expenses</option></select> <br/>");
    }
}
//budget sliding toggling
var x = false;
function budToggle()
{

    if(!x)
    {
        $(".bud").css("height", "200px");
        x = true;
    }
    else{
        $(".bud").css("height", "50px");
        x = false;
    }

}
//output budget details to html
function writeBud(budget,count,left)
{
    if(count>0)
    {
        $("#view_budget").append("<div class=\"bud\">" +budget.name + " Budget " + "<span class=\"moveRight\"> $" +budget.amount + "</span></br> Budget Amount Left  <span class=\"moveRight\"> $" +left + "</span><br/>Budget Type  <span class=\"moveRight\"> " +budget.type + "</span></br> Budget For  <span class=\"moveRight\"> " +budget.budfor + "</span><div> ");  
        
    }

        close();
}
//adds budgets to html
function doBudget()
{
    
        if ($("#budAmount").val().length != 0 && $("#budName").val().length != 0) {

            var name = $("#budName").val();
            var type = $("#budType").val();
            var budFor = $("#budFor").val();
            var budAmount = $("#budAmount").val();

            document.getElementById("budName").value = "";
            document.getElementById("budAmount").value = "";

            allBuds[budsCount] = new Budget(name, budAmount, type, budFor);
            budsCount += 1;
            budgetExps(allBuds, allExp);
        }
        else {
            alert("please fill in all form details");
        }
}
function Submit(FormName)
{
    document.getElementsByName(FormName)[0].submit();
}

// budget expenses left
function budgetExps(BudArray,ExpArray)
{
    $("#view_budget").html("");
    for(var i=0;i< budsCount;i++)
    {
        if (BudArray[i].budfor == "allExp") {
                var amount = 0;
                amount = BudArray[i].amount - totalExp;
                writeBud(BudArray[i], budsCount,amount)
        }
        else{
            for (var x=0;x < expCount;x++)
            {
                if (BudArray[i].budfor == allExp[x].name)
                {
                    var expSum = 0;

                    for (var z = 0; z < expCount;z++ )
                    {
                        if(allExp[z].name == allExp[x].name)
                        {
                            alert(expSum);
                            expSum = expSum + allExp[z].amnt;
                        }
                    }
                    alert(expSum);
                        var amnt = BudArray[i].amount - expSum;
                        writeBud(BudArray[i], budsCount, amnt);
                }
            }

        }
        
    }
}
