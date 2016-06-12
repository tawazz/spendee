{% extends 'templates/api.php' %}

{% block content %}

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-orange">
          <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12">
                    <span class="tx-2x">{{date}} Tags</span>
                </div>
            </div>
          </div>
          <!-- /.panel-heading -->
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
    Morris.Bar({
    element: 'morris-bar-tags',
    data: [
      {% for tag,cost in exptags %}
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
