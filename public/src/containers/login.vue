<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4">
        <form role="form" action="/login" method="post" name="login">
          <div class="card card-login">
            <div class="card-header text-center" data-background-color="rose">
              <div class="text-center img-responsive"><img src="/images/icon.png" alt="logo" style="width:auto; height:70px;"/></div>
              <div class="text-center"><h3 class="brand-font">Spendee</h3></div>
              <h4 class="card-title">Login</h4>
              <div class="social-line">
                <a href="/login/google?go=google" class="btn btn-lg btn-raised btn-warning">
                  <i class="fa fa-google"></i>
                </a>
              </div>
            </div>
            <div class="card-content">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="mdi mdi-face"></i>
                </span>
                <div class="form-group label-floating is-empty">
                  <label class="control-label">User name</label>
                  <input type="text" name="username" class="form-control" autocomplete="off" style="cursor: auto;">
                  <span class="material-input"></span></div>
                </div>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="mdi mdi-lock"></i>
                  </span>
                  <div class="form-group label-floating is-empty">
                    <label class="control-label">Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="off" style="cursor: auto;">
                    <span class="material-input"></span></div>
                  </div>
                  <csrf />
                </div>
                <div class="footer text-center">
                  <button type="submit" class="btn btn-info btn-raised btn-lg">Login</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
</template>

<script>
import csrf from '@/components/csrf'
export default {
  components:{
    csrf
  }
}
</script>
