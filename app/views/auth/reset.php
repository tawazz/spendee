{% extends 'templates/plain.php' %}

{% block content %}
<div class="row">
  <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4">
    <div class="card card-login">
      <div class="card-header text-center" data-background-color="rose">
        <div class="text-center img-responsive"><img src="/images/icon.png" alt="logo" style="width:auto; height:70px;"/></div>
        <h3 class="brand-font text-center">Spendee</h3>
        <div class="page-header text-center">
          <h2>Reset Password</h2>
        </div>
      </div>
      <div class="card-content">
        <form action="{{urlFor('post.register')}}" method="post" name="reg">
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
          <label></label><br/><input type="submit" class="btn btn-info btn-raised" style="padding:8px 50px;" value="Sign Up"/></br>
          <div id="error"></div>
        </form>
      </div>
    </div>
  </div>
</div>
{% endblock %}
