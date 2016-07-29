<!DOCTYPE html>
<html lang="en">
    <head>
        {% include "parts/meta.php" %}
        {% include "parts/css.php" %}
        {% block css %}{% endblock%}
        {% include "parts/scripts.php" %}
    </head>
    <body>
        {% include "parts/nav.php" %}
        <div class="container">
        {% include "parts/flash.php" %}
        {% if auth %}
        {% include "parts/dash.php" %}
        {%endif%}
        {% block content %}{% endblock %}
        {% include "parts/footer.php" %}
        {% block js %}{% endblock%}
        </div>
    </body>
</html>
