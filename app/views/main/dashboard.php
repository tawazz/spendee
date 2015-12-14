{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{date}} Earnings & Spendings</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-bar-chart"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{date}} savings</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-line-chart"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-6">
        <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{date}} Incomes</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-bar-income"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{date}} Expenses</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-bar-expenses"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>
<script type="text/javascript">
  Morris.Bar({
  element: 'morris-bar-chart',
  data: [
    {%for key,val in earned%}
        { d: '{{(date ~'-'~key~'-1')|date('M')}}', a: {{val}}, b: {{spent[key]}}, c: {{val - spent[key] }} },
    {%endfor%}
  ],
  xkey: 'd',
  ykeys: ['a', 'b' ,'c'],
  labels: ['Incomes', 'Expenses' , 'Balance'],
  barColors: ['#2ecc71','#e74c3c','#60677A'],
  preUnits:'$',
  resize:true
  });

  new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'morris-line-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    {%for key,val in earned%}
          { d: '{{(date ~'-'~key~'-1')}}', a: {{val}}, b: {{spent[key]}}, c: {{val - spent[key] }} },
    {%endfor%}
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'd',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['a','b','c'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['earned','spent','saved'],
  lineColors:['#47C9AF','red','black'],
  goalLineColors:['#d9edf7'],
  dateFormat: function (x) { return moment(x).format(" MMMM YYYY"); },
  preUnits:'$',
  xLabelFormat:function (x) { return moment(x).format("MMM"); },
  resize:true
  });

  Morris.Bar({
  element: 'morris-bar-income',
  data: [
    {%for inc in allIncomes %}
        { inc : '{{ inc.name }}', cost : {{inc.cost}} },
    {%endfor%}
  ],
  xkey: 'inc',
  ykeys: ['cost'],
  labels: ['Income'],
  barColors: ['#2ecc71'],
  preUnits:'$',
  resize:true
  });
  Morris.Bar({
  element: 'morris-bar-expenses',
  data: [
    {%for exp in allExpenses %}
        { exp : '{{ exp.name }}', cost : {{exp.cost}} },
    {%endfor%}
  ],
  xkey: 'exp',
  ykeys: ['cost'],
  labels: ['Expense'],
  preUnits:'$',
  barColors: ['#e74c3c'],
  resize:true
  });
</script>
{% endblock %}
