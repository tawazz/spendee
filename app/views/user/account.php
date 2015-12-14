{% extends 'templates/plain.php' %}

{% block content %}
<div class="row">
  <div class="col-xs-12">
    <div class="col-md-4">
        <img src="{{baseUrl}}/images/user.png" class="img-rounded img-responsive" />
        <br/>
        <br/>
        <form action="update/user" method="post" enctype="multipart/form-data">
            <label>Registered Username</label>
            <input type="text" class="form-control" value="tawazz" disabled>
            <label>Registered Email</label>
            <input type="email" class="form-control" placeholder="{{auth.email}}" name="email">
            <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
            <br>
            <button type="submit" class="btn btn-primary">Update Details</button>
            <br/><br/>
        </form>
    </div>
    <div class="col-md-8">
        <div class="form-group col-md-8">
            <form action="update/password" method="post" >
                <h3>Change Your Password</h3>
                <br />
                <label>Enter Old Password</label>
                <input type="password" class="form-control" name="old_password">
                <label>Enter New Password</label>
                <input type="password" class="form-control"  name="new_password">
                <label>Confirm New Password</label>
                <input type="password" class="form-control"  name="repeat_password" />
                <br>
                <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
  </div>
</div>
{% endblock %}
