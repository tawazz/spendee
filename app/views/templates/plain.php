<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="description" content="personal money management webapp"/>
        <meta name="msapplication-navbutton-color" content="#ec5959"/>
        <link rel="shortcut icon" href="{{ baseUrl() }}/images/icon.png"/>
        <link rel="apple-touch-icon" href="{{ baseUrl() }}/images/icon.png" />
        <title>{{ brand }}</title>
        {% include "parts/css.php" %}
        {% include "parts/scripts.php" %}
    </head>
    <body>
        {% include "parts/nav.php" %}
        <div class="container">
        {% include "parts/flash.php" %}
        {% block content %}{% endblock %}
        {% include "parts/footer.php" %}
        </div>
    </body>
</html>
