{% extends 'templates/default.php' %}

{% block content %}
<div class="page-header">
    <h1>Contact Me<br><small>Let Me Know What I Can Do For You.</small></h1>
  </div>
<div class="col-xs-12 col-sm-6 col-sm-offset-3">
  <form class="form-horizontal" action="tawazz.net/me/contact.php" method="post" onsubmit="return validate(this);">
       <div class="form-group">
       <label for="input Name" class="col-sm-2 control-label">Name :</label>
       <div class="col-sm-10">
           <input type="text" class="form-control" name="name">
       </div>
       </div>
       <div class="form-group">
       <label for="inputEmail" class="col-sm-2 control-label">Email :</label>
       <div class="col-sm-10">
           <input type="email" class="form-control" name="email">
       </div>
       </div>
       <div class="form-group">
       <label for="inputPhone" class="col-sm-2 control-label">Phone :</label>
       <div class="col-sm-10">
           <input type="text" class="form-control" name="phone">
       </div>
       </div>
       <div class="form-group">
       <label for="inputTextArea" class="col-sm-2 control-label">Message :</label>
       <div class="col-sm-10">
           <textarea name="msg" class="form-control" style="width: 100%" rows="10" ></textarea>
       </div>
       </div>
       <div class="form-group">
       <div class="col-sm-offset-2 col-sm-10">
           <button type="submit" class="btn btn-primary">Send<span class="glyphicon glyphicon-send" aria-hidden="true"></span></button>
       </div>
       </div>
   </form>
</div>

{% endblock %}
