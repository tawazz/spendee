{% set brand="Spendee" %}
{% set address = "Perth<br> Australia" %}
{% set phone = "+61 401 234 567" %}
{% set email = "admin@tawazz.net" %}
{% set ver = "v2.6.1" %}

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="description" content="personal money management webapp"/>
        <meta name="msapplication-navbutton-color" content="#FF3D00"/>
        <link rel="shortcut icon" href="/spendee/images/icon.png"/>
		    <meta name="theme-color" content="#ff5722">
        <link rel="apple-touch-icon" href="/spendee/images/icon.png" />
        <title>Spendee</title>
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
        </div>
    </body>
</html>
