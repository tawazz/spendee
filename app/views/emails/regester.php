{% extends 'templates/email.php' %}


{% block content %}
<h2 style="font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 18px; line-height: 1.2em; color: #111111; font-weight: 200; margin: 40px 0 5px; padding: 0;">
  Thank you for regestering for spendee
</h2>
<h2 style="font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 18px; line-height: 1.2em; color: #111111; font-weight: 200; margin: 40px 0 5px; padding: 0;">Message</h2>
 <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">
   Activate your account below.
   <a href="http://spendee.dev/activate?email={{email}}&active_hash={{active_hash}}"></a>
 </p>


{% endblock %}
