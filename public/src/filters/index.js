export default{
  date:function (D) {
    return moment(D).format("dddd, MMMM Do YYYY");
  },
  formatMoney:function(n,c, d, t){
        c = isNaN(c = Math.abs(c)) ? 2 : c;
        d = d == undefined ? "." : d;
        t = t == undefined ? "," : t;
        var s = n < 0 ? "-" : "";
        var i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));
        var j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   }
}
