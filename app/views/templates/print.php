<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Spendee</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <meta name="author" content="Tawanda Nyakudjga">
    <meta name="keywords" content="Spendee, personal, money, finance,track, spending, managment,budget,tracker,expenses,incomes">
    <meta name="description" content="Spendee is a personal money management app that helps track your spending and other aspects of your finances effortlessly."/>
    <meta name="msapplication-navbutton-color" content="#FF3D00"/>
    <link rel="shortcut icon" href="images/icon.png"/>
    <meta name="theme-color" content="#ff5722">
    <link rel="apple-touch-icon" href="/images/icon.png" />
    <link href='https://fonts.googleapis.com/css?family=Lobster+Two:400,200italic,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,700' rel='stylesheet' type='text/css'>
    <link href='{{assets("/css/app.css")}}' rel='stylesheet' type='text/css'>
    {% block css %}{% endblock %}
  </head>
  <body>
    {% block content %}{% endblock %}
    {% block js %}{% endblock %}
    <script type="text/javascript" src='{{assets("/js/app.js")}}'></script>
  </body>
</html>
