{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
          <div class="panel-body" {% if not exptags %} style="min-height: 370px;display: flex;justify-content: center; align-items: center;" {% endif %}>
              <div id="morris-bar-tags">
              </div>
              {% if not exptags %} No Data Available {% endif %}
          </div>
          <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
    </div>
  </div>

  <script type="text/javascript">
  {% if exptags %}
    Morris.Donut({
    element: 'morris-bar-tags',
    data: [
      {% for tag,cost in exptags %}
          {label: "{{tag|raw}}", value:{{ cost }} },
      {% endfor%}
    ],
    colors: ['#FF7043'],
    formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
    resize:false
    });
  {% endif %}
    </script>

{% endblock %}
