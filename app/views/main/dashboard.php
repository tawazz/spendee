{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{ appData.nav.display }} Earnings & Spendings</span>
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
                      <span class="tx-2x">{{ appData.nav.display }} savings</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body" {% if not appData.exp_data %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                <div id="morris-line-chart"></div>
                 {% if not appData.exp_data %} No Data Available {% endif %}
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
                      <span class="tx-2x">{{ appData.nav.display }} Incomes</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body" {% if not appData.inc_data %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                <div id="morris-bar-income">

                </div>
               {% if not appData.inc_data %} No Data Available {% endif %}
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
                      <span class="tx-2x">{{ appData.nav.display }} Expenses</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body" {% if not appData.exp_data %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                <div id="morris-bar-expenses">
                </div>
                {% if not appData.exp_data %} No Data Available {% endif %}
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12">
                      <span class="tx-2x">{{ appData.nav.display }} Tags</span>
                  </div>
              </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body" {% if not appData.exp_tags %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
                <div id="morris-bar-tags">
                </div>
                {% if not appData.exp_tags %} No Data Available {% endif %}
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
        { d: '{{(appData.nav.current.year ~'-'~key~'-1')|date('M')}}', a: {{val}}, b: {{spent[key]}}, c: {{val - spent[key] }} },
    {%endfor%}
  ],
  xkey: 'd',
  ykeys: ['a', 'b' ,'c'],
  labels: ['Incomes', 'Expenses' , 'Balance'],
  barColors: ['#00E676','#e74c3c','#60677A'],
  preUnits:'$',
  resize:true
  });

  {% if allIncomes or allExpenses %}
  new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'morris-line-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    {%for key,val in earned %}
          { d: '{{(appData.nav.current.year ~'-'~key~'-1')}}', a: {{val}}, b: {{spent[key]}}, c: {{val - spent[key] }} },
    {%endfor%}
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'd',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['a','b','c'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['earned','spent','saved'],
  lineColors:['#00E676','#F16C63','#333333'],
  goalLineColors:['#d9edf7'],
  dateFormat: function (x) { return moment(x).format(" MMMM YYYY"); },
  preUnits:'$',
  xLabelFormat:function (x) { return moment(x).format("MMM"); },
  resize:true
  });
  {% endif %}

  {% if allIncomes%}
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
  barColors: ['#00E676'],
  preUnits:'$',
  resize:true
  });
  {% endif %}

  {% if allExpenses %}
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
{% endif %}

{% if appData.exp_tags %}
Morris.Bar({
element: 'morris-bar-tags',
data: [
  {% for tag,cost in appData.exp_tags %}
      {tag: "{{tag|raw}}", cost:{{ cost }} },
  {% endfor%}
],
xkey: 'tag',
ykeys: ['cost'],
labels: ['spent'],
barColors: ['#FF7043'],
preUnits:'$',
resize:true
});
{% endif %}
</script>
{% endblock %}
