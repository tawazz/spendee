<template id="nav">
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
            <router-link class="navbar-brand brand-font" :to="{ path: '/expenses'}">
              <img src="../../../images/navicon.png" alt="S" style="max-width:40px; margin-top: -7px;">pendee
            </router-link>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
              <li><router-link :to="{ path: '/expenses'}">Expenses</router-link></li>
              <li><router-link :to="{ path: '/incomes' }">Incomes</router-link></li>
              <li><router-link :to="{ path: '/budgets' }">Budgets</router-link></li>
              <li><router-link :to="{ path: '/overview' }">Overview</router-link></li>
              <li class="dropdown">
                <a href="" class="dropdown-toggle" data-toggle="dropdown"><span class="text-capitalize">{{user.username}}</span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><router-link :to="{ path: '/settings' }">Profile</router-link></li>
                    <li class="divider"></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav><!--end navbar-->
  </div>

</template>
<script type="text/javascript">
  export default {
    data: function () {
      return {
        user: Window.user
      }
    }
  }
</script>
