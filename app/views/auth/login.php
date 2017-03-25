{% extends 'templates/plain.php' %}

{% block content %}
<div class="row" style="margin-top:10px;">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center img-responsive"><img src="{{ baseUrl() }}/images/icon.png" height="100" alt="logo" /></div>
                <div class="text-center"><h3 class="brand-font">Spendee</h3></div>
                <form role="form" action="{{urlFor('post.login')}}" method="post" name="login" onsubmit="return checkLogin()">
                  <div class="form-group{%if errors.username%} has-error{%endif%}">
                    <input type="text" name="username" placeholder="User name" class="form-control">{{errors.username}}
                  </div>
                  <div class="form-group{%if errors.password%} has-error{%endif%}">
                    <input type="password" name="password" placeholder="Password" class="form-control"  >{{errors.password}}
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember">
                      <span class="checkbox-material"><span class="check"></span></span> Remember Me
                    </label>
                    <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
                  </div>
                  <button type="submit" class="btn btn-info btn-raised col-xs-12 ">LOGIN</button>
                    <div class="form-group col-xs-12 text-center">
                      <span class="text-danger">{{errors.login}}</span>
                        <br/><p>Don't have an Account? Sign up <a href="{{urlFor('register')}}" style="color:#5bc0de;">here</a> </p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
