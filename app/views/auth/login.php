{% extends 'templates/plain.php' %}

{% block content %}
<div class="row" style="margin-top:10px;">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center img-responsive"><img src="{{ baseUrl() }}/images/icon.png" height="100" alt="logo" /></div>
                <div class="page-header text-center"><h3 class="brand-font">Spendee</h3></div>
                <form role="form" action="{{urlFor('post.login')}}" method="post" name="login" onsubmit="return checkLogin()">
                  <div class="form-group{%if errors.username%} has-error{%endif%}">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">{{errors.username}}
                  </div>
                  <div class="form-group{%if errors.password%} has-error{%endif%}">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"  >{{errors.password}}
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
                  </div>
                  <button type="submit" class="btn btn-info col-xs-6 col-xs-offset-3">LOGIN</button>
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
