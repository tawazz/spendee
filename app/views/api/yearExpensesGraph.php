{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
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
        Morris.Donut({
        element: 'morris-bar-expenses',
        data: [
          {%for exp in allExpenses %}
              { label : '{{ exp.name }}', value: {{exp.cost}} },
          {%endfor%}
        ],
        colors: ['#e74c3c'],
        formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
        resize:false
        });
    {% endif %}
    </script>

{% endblock %}
