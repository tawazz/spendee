{% extends 'templates/plain.php' %}

{% block content %}
<div class="page-header">
    <h1>Contact Me<br><small>Let Me Know What I Can Do For You.</small></h1>
</div>
<div class="col-xs-12 col-sm-8 col-sm-offset-2">
  <form class="form-horizontal" action="{{urlFor('post.contact')}}" method="post" onsubmit="return validate(this);">
       <div class="form-group">
         <input type="text" class="form-control" name="name" placeholder="Name">
       </div>
       <div class="form-group">
         <input type="email" class="form-control" name="email" placeholder="Email">
       </div>
       <div class="form-group">
         <input type="text" class="form-control" name="phone" placeholder="Phone">
       </div>
       <div class="form-group">
         <label for="inputTextArea" class="control-label">Message</label>
         <textarea name="msg" class="form-control" style="width: 100%;border-right:1px solid #eee; border-left:1px solid #eee;border-top:1px solid #eee" rows="10" ></textarea>
       </div>
         <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}">
       <div class="form-group">
             <button type="submit" class="btn btn-info btn-raised" style="padding: 8px 50px">Send</span></button>
       </div>
       </div>
   </form>
</div>

{% endblock %}
