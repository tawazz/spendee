{% extends 'templates/plain.php' %}

{% block content %}
<div class="row">
<div class="col-xs-12 col-sm-6 col-sm-offset-3">
    <div class="panel panel-default">
        <div class="panel-body">
          <div class="text-center img-responsive"><img src="{{baseUrl}}/images/icon.png" height="100" alt="logo" /></div>
          <h3 class="brand-font text-center">Spendee</h3>
            <div class="page-header text-center"><h2>Register Your Details </h2></div>
                <form action="{{urlFor('post.register')}}" method="post" name="reg" onsubmit="return checkReg()">
                     <div class="form-group{%if errors.firstname%} has-error{%endif%}">
                         <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" value="{{values.firstname}}" >
                     </div>
                    <div class="form-group{%if errors.lastname%} has-error{%endif%}">
                         <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="{{values.lastname}}">
                     </div>
                    <div class="form-group{%if errors.username%} has-error{%endif%}">
                         <label>Username*</label>
                        <input type="text" name="username" class="form-control" value="{{values.username}}">{{errors.username}}
                     </div>
                    <div class="form-group{%if errors.password%} has-error{%endif%}">
                         <label>Password*</label>
                        <input type="password" name="password" class="form-control" >{{errors.password}}
                     </div>
                    <div class="form-group{%if errors.password_again%} has-error{%endif%}">
                         <label>Re-enter Password*</label>
                        <input type="password" name="password_again" class="form-control">{{errors.password_again}}
                     </div>
                     <div class="form-group{%if errors.email%} has-error{%endif%}">
                         <label>Email*</label>
                        <input type="email" name="email" class="form-control" value="{{values.email}}" >{{errors.email}}
                     </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
                <label> </label><br/><input type="submit" class="btn btn-info" style="padding:8px 50px;" value="Sign Up"/></br>
                <div id="error"></div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
