{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
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
          <div class="panel-body" {% if not allExpenses %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
              <div id="morris-bar-expenses">
              </div>
              {% if not allExpenses %} No Data Available {% endif %}
          </div>
          <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
    </div>
  </div>

  <script type="text/javascript">
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
    </script>

{% endblock %}
