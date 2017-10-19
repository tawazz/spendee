{% extends 'templates/plain.php' %}

{% block content %}
<div class="row">
<div class="col-xs-12 col-sm-6 col-sm-offset-3">
    <div class="panel panel-default">
        <div class="panel-body">
          <div class="text-center img-responsive"><img src="/images/icon.png" height="100" alt="logo" /></div>
          <h3 class="brand-font text-center">Spendee</h3>
            <div class="page-header text-center"><h2>Register Your Details </h2></div>
                <form action="{{urlFor('post.register')}}" method="post" name="reg" onsubmit="return checkReg()">
                     <div class="form-group{%if errors.firstname%} has-error{%endif%}">
                        <input type="text" name="firstname" placeholder="First Name" class="form-control" value="{{values.firstname}}" >
                     </div>
                    <div class="form-group{%if errors.lastname%} has-error{%endif%}">
                        <input type="text" name="lastname" placeholder="Last Name" class="form-control" value="{{values.lastname}}">
                     </div>
                    <div class="form-group{%if errors.username%} has-error{%endif%}">
                        <input type="text" name="username" placeholder="Username*" class="form-control" value="{{values.username}}">{{errors.username}}
                     </div>
                    <div class="form-group{%if errors.password%} has-error{%endif%}">
                        <input type="password" name="password" placeholder="Password*" class="form-control" >{{errors.password}}
                     </div>
                    <div class="form-group{%if errors.password_again%} has-error{%endif%}">
                        <input type="password" name="password_again" placeholder="Re-enter Password*" class="form-control">{{errors.password_again}}
                     </div>
                     <div class="form-group{%if errors.email%} has-error{%endif%}">
                        <input type="email" name="email" class="form-control" placeholder="Email*" value="{{values.email}}" >{{errors.email}}
                     </div>
                     {{ csrf()|raw }}
                <label> </label><br/><input type="submit" class="btn btn-info btn-raised" style="padding:8px 50px;" value="Sign Up"/></br>
                <div id="error"></div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
