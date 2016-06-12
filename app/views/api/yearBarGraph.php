{% extends 'templates/api.php' %}

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
    </script>

{% endblock %}
