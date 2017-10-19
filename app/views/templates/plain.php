<!DOCTYPE html>
<html lang="en">
    <head>
        {% include "parts/meta.php" %}
        {% include "parts/css.php" %}
        {% include "parts/scripts.php" %}
        {% block css %}{% endblock%}
    </head>
    <body>
        {% include "parts/nav.php" %}
        <div class="container">
        {% include "parts/flash.php" %}
        {% block content %}{% endblock %}
        {% include "parts/footer.php" %}
        </div>
        {% block js %}{% endblock%}
    </body>
</html>
