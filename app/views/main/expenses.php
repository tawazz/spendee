{% extends vue_template %}
{% block css %}
<style media="screen">
  .select2-container .select2-search--inline .select2-search__field{
    width: 70px;
  }
  .select2-container--classic .select2-selection--multiple{
    height: 60px;
  }
</style>
{% endblock %}
{% block content %}
  <div id="app"></div>
{% endblock %}
