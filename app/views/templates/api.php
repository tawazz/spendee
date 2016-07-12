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
        <title></title>
        {% include "parts/css.php" %}
        {% include "parts/scripts.php" %}
    </head>
    <body style="padding-top:0px">
      <div class="container-fluid">
        {% block content %}{% endblock %}
      </div>

    </body>
</html>
