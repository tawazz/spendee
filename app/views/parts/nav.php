<div class="container">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" >
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          {%if not auth %}
          <a class="navbar-brand brand-font" href="{{urlFor("home")}}"><img src="{{baseUrl}}/images/navicon.png" alt="logo" style=" max-width:40px;margin-top: -7px;">pendee</a>
          {%else%}
          <a class="navbar-brand brand-font" href="{{urlFor("expenses")}}"><img src="{{baseUrl}}/images/navicon.png" alt="logo" style="max-width:40px; margin-top: -7px;">pendee</a>
          {%endif%}
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            {%if not auth %}
            <li><a href="{{urlFor('login')}}">Login</a></li>
            <li><a href="{{urlFor('register')}}">Register</a></li>
            <li class="dropdown">
            <a href="{{urlFor('about')}}" class="dropdown-toggle" data-toggle="dropdown"><span class="text-capitalize">About</span><span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{urlFor('about')}}">About</a></li>
                <li><a href="{{urlFor('contact')}}">Contact Us</a></li>
                <!--li class="divider"></li>
                <li><a href="{{urlFor('help')}}">Help</a></li-->
            </ul>
            {%else%}
            <li><a href="{{urlFor('expenses')}}">Expenses</a></li>
            <li><a href="{{urlFor('incomes')}}">Incomes</a></li>
            <li><a href="{{urlFor('dashboard')}}">Dashboard</a></li>
            <li class="dropdown">
            <a href="{{urlFor('account')}}" class="dropdown-toggle" data-toggle="dropdown"><span class="text-capitalize">{{auth.username}}</span><span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{urlFor('account')}}">Account</a></li>
                <li class="divider"></li>
                <li><a href="{{urlFor('logout')}}">Logout</a></li>
            </ul>
            {%endif%}
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--end navbar-->
</div>
