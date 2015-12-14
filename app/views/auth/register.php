{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
<div class="col-xs-12 col-sm-6 col-sm-offset-3">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="page-header text-center"><h2>Register Your Details </h2></div>
                <form action="{{urlFor('post.register')}}" method="post" name="reg" onsubmit="return checkReg()">
                     <div class="form-group{%if errors.firstname%} has-error{%endif%}">
                         <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" placeholder="Enter Your First Name">
                     </div>
                    <div class="form-group{%if errors.lastname%} has-error{%endif%}">
                         <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" placeholder="Enter Your Last Name">
                     </div>
                    <div class="form-group{%if errors.username%} has-error{%endif%}">
                         <label>UserName</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter Your User Name">{{errors.username}}
                     </div>
                    <div class="form-group{%if errors.password%} has-error{%endif%}">
                         <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Your password">{{errors.password}}
                     </div>
                    <div class="form-group{%if errors.password_again%} has-error{%endif%}">
                         <label>Re-Enter Password Again</label>
                        <input type="password" name="password_again" class="form-control" placeholder="Re-Enter Your password">{{errors.password_again}}
                     </div>
                     <div class="form-group{%if errors.email%} has-error{%endif%}">
                         <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Your Email Address">{{errors.email}}
                     </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
                <label> </label><br/><input type="submit" value="SignUp"/></br>
                <div id="error"></div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}
