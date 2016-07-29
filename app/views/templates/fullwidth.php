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
        <div class="container-fluid" style="margin-top:-5px;">
          <div class="row">
            {% include "parts/flash.php" %}
            {% block content %}{% endblock %}
            {% include "parts/footer.php" %}
          </div>
        </div>
    </body>
</html>
