{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12">
                    <span class="tx-2x">{{date}} Incomes</span>
                </div>
            </div>
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body" {% if not allIncomes %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
              <div id="morris-bar-income">

              </div>
             {% if not allIncomes %} No Data Available {% endif %}
          </div>
          <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
    </div>
  </div>

  <script type="text/javascript">
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
    barColors: ['#2ecc71'],
    preUnits:'$',
    resize:true
    });
  {% endif %}
    </script>

{% endblock %}
