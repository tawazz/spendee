{% extends 'templates/plain.php' %}

{% block content %}
  <div id="app">

  </div>
{% endblock %}

{% block css %}
<style media="screen">
  .card-login i {
    max-width: 24px;
    overflow: hidden;
    display: block;
  }
</style>
{% endblock %}

{% block js %}
<script type="text/javascript">
  Window.csrf = {{ csrf|json_encode()|raw }};
</script>
<script type="text/javascript" src="/assets/js/app.js"></script>
{% endblock %}
