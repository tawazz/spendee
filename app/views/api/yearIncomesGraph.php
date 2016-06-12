{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
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
    Morris.Donut({
    element: 'morris-bar-income',
    data: [
      {%for inc in allIncomes %}
          { label : '{{ inc.name }}', value : {{inc.cost}} },
      {%endfor%}
    ],
    colors: ['#2ecc71'],
    formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
    resize:false
    });
  {% endif %}
    </script>

{% endblock %}
