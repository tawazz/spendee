{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
          <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12">
                    <span class="tx-2x">{{date}} savings</span>
                </div>
            </div>
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body" {% if not allExpenses %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
              <div id="morris-line-chart"></div>
               {% if not allIncomes %} No Data Available {% endif %}
          </div>
          <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
    </div>
  </div>

  <script type="text/javascript">
  {% if allIncomes or allExpenses %}
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
  {% endif %}
    </script>

{% endblock %}
