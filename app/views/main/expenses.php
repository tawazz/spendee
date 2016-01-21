{% extends 'templates/default.php' %}
{% block css %}
<style media="screen">
  .select2-container .select2-search--inline .select2-search__field{
    width: 70px;
  }
  .select2-container--classic .select2-selection--multiple{
    height: 60px;
  }
</style>
{% endblock %}
{% block content %}
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h2>Expenses</h2>
        </div>
        <div class="col-sm-12">
            <button style="margin-bottom: 20px;" type="button" id="addItem" class="btn btn-info" data-toggle="modal" data-target="#addUser">
                <i class="fa fa-plus"></i> Add Expenses
            </button>
        </div>
  </div>
</div>
<div class="row">
    {%if allExpenses %}
<div class="col-xs-12 col-sm-6">
  {% set dates =[] %}
  {% set totals =[] %}
  {%for exp in allExpenses %}
    {% set dates = dates|merge({(exp.date):NULL}) %}
  {% endfor %}

  {% for date in dates|keys %}
    {% set total = 0 %}
  <div class="col-sm-12">
      <div class="panel panel-danger">
          <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <h3><script type="text/javascript">
                        document.write(moment('{{date}}').format("dddd, MMMM Do YYYY"));
                      </script></h3>
                  </div>
              </div>
          </div>
          {%for exp in allExpenses%}
            {% if date == exp.date %}
            {% set total = total+exp.cost %}
            <a href="{{baseUrl}}/expense/{{exp.name}}">
                <div class="panel-footer">
                    <span class="pull-left">{{exp.name}}</span>
                    <span class="pull-right"><i class="fa fa-usd">{{exp.cost}}</i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
            {% endif %}
          {% endfor %}
            {% set totals = totals|merge({(date):total}) %}
          <div class="panel-footer">
              <span class="pull-left">Total</span>
              <span class="pull-right"><i class="fa fa-usd">{{total|number_format(2, '.')}}</i></span>
              <div class="clearfix"></div>
          </div>
      </div>
  </div>
  {% endfor %}
</div>
    {% else %}
        <div class="col-xs-12 col-sm-6">
          <!-- /.row -->
            <div class="row" >
                  <div class="col-lg-12">
                        <img src="{{baseUrl()}}/images/exp.png" class="img-responsive" alt="no data available" style="margin-left: auto;margin-right: auto;" />
                  </div>
            </div>
        </div>
    {% endif %}
<div class="col-xs-12 col-sm-6">
  <!-- /.row -->
  <div class="row" >
      <div class="col-lg-12">
          <div class="panel panel-info">
              <div class="panel-heading">
                  {{date}} Spending Pattern
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body" {% if not items %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                  <div id="morris-line-chart"></div>
                  {% if not items %} No Data Available {% endif %}
              </div>
              <!-- /.panel-body -->
          </div>
          <!-- /.panel -->
      </div>
      <div class="col-lg-12">
          <div class="panel panel-info">
              <div class="panel-heading">
                  {{date}} Spending on Items
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body" {% if not totals %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                  <div id="morris-pie-chart"></div>
                   {% if not totals %} No Data Available {% endif %}
              </div>
              <!-- /.panel-body -->
          </div>
          <!-- /.panel -->
      </div>
  </div>
  <!-- /.row -->
</div>
</div>
<div class="modal fade" id="addExp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Add Expense </h4>
      </div>
      <div class="modal-body">
          <form name="addForm" id="addForm" method="post" action="{{baseUrl}}/expenses/add">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Item Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="cost" id="money" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <input type="text" class="form-control datepicker" name="date" placeholder="Date" data-provide="datepicker" onfocus="blur();" onkeydown="return false">
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width:100%;height:50px;">
                {% for tag in tags %}
                <option value="{{tag.id}}">{{ tag.name }}</option>
                {% endfor %}
              </select>
            </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="save" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
{% if totals is not empty %}
new Morris.Line({
// ID of the element in which to draw the chart.
element: 'morris-line-chart',
// Chart data records -- each entry in this array corresponds to a point on
// the chart.
data: [
  {% for D, tot in totals %}
    { day: '{{ D|date("Y-m-d") }}', value: {{tot}} },
  {% endfor %}
],
// The name of the data record attribute that contains x-values.
xkey: 'day',
// A list of names of data record attributes that contain y-values.
ykeys: ['value'],
// Labels for the ykeys -- will be displayed when you hover over the
// chart.
labels: ['Spent'],
yLabelFormart: function (y) { return "$"+y.toString(); },
dateFormat: function (x) { return moment(x).format("dddd, MMMM Do YYYY"); },
preUnits:'$',
xLabelFormat:function (x) { return moment(x).format("MMM Do"); },
lineColors:['#F16C63'],
goalLineColors:['#d9edf7'],
resize:true
});
{% endif %}
</script>
<script type="text/javascript">
Morris.Donut({
element: 'morris-pie-chart',
data: [
  {% for item in items %}
  {label: "{{item.name|raw}}", value:{{item.cost}} },
  {%endfor%}
],
colors:['#CF000F']
});
</script>
<script type="text/javascript">
    $.fn.select2.defaults.set("theme", "classic");
    $("#tags").select2({
      tags: "true",
      placeholder: "Tags",
      allowClear: true,
    });
</script>
{% endblock %}
