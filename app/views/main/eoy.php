{% extends 'templates/print.php' %}
{% block css %}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
<style>
.ct-label {
  fill: #ffffff;
  color: #ffffff;
  font-size: 2rem;
  line-height: 1;
}
.badge{
  background-color: #f16c63;
  width:100px;
}
body{
  padding-top: 0;
}
</style>
{% endblock %}
{% block content %}
<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title text-center">Top Expenses in 2018</h3>
  </div>
  <div class="panel-body" style="height:100vh;">
    <div class="container">
      <div class="row">
        <div class="col-sm-8">
          <div class="ct-chart ct-perfect-fourth">

          </div>
        </div>
        <div class="col-sm-4">
          <ul class="list-group">
            {% for tag,value in data.tags %}
            <li class="list-group-item">
              <span class="badge badge-danger">${{value|number_format(2, '.', ',')}}</span>
              {{ tag}}
            </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
{% endblock %}
{% block js %}
<script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
<script >
(function () {
  var data = {
  labels: [
     {% for tag in data.tags|keys|slice(0, 5) %}
        '{{tag}}',
     {% endfor %}
  ],
  series: [
    {% for tag in data.tags|slice(0, 5) %}
       '{{tag}}',
    {% endfor %}
  ]
  };

  var options = {
    labelOffset: 60,
    labelInterpolationFnc: function(value) {
      return value;
    }
  };

  var responsiveOptions = [
  ['screen and (min-width: 640px)', {
    labelOffset: 30,
    chartPadding: 10
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 30,
    chartPadding: 20
  }]
  ];

  new Chartist.Pie('.ct-chart', data, options,responsiveOptions);
})();


</script>
{% endblock %}
