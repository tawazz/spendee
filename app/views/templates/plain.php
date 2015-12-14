{% set brand="tawazz.net" %}
{% set address = "Perth<br> Australia" %}
{% set phone = "+61 401 234 567" %}
{% set email = "admin@tawazz.net" %}

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
        <link rel="shortcut icon" href="/spendee-v2/images/icon.png"/>
        <link rel="apple-touch-icon" href="/spendee-v2//images/icon.png" />
        <title></title>
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
