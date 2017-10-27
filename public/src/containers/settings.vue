<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>User Profile</h1>
        <br/>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="col-md-5">
          <div class="card card-testimonial">
            <div class="header">
                <div class="card-avatar">
                    <a href="#">
                        <img class="img" src="/images/user.png">
                    </a>
                </div>
            </div>
              <div class="card-content">
                <form action="/update/user" method="post" enctype="multipart/form-data">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="mdi mdi-face"></i>
                    </span>
                    <div class="form-group label-floating">
                      <label class="control-label">Username</label>
                      <input type="text" name="username" class="form-control" autocomplete="off" v-model="user.username" style="cursor: auto;" disabled>
                      <span class="material-input"></span></div>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="mdi mdi-email"></i>
                    </span>
                    <div class="form-group label-floating">
                      <label class="control-label">Email</label>
                      <input type="text" class="form-control" autocomplete="off" style="cursor: auto;" v-model="user.email" name="email">
                      <span class="material-input"></span></div>
                  </div>
                    <csrf />
                    <button type="submit" class="btn btn-info btn-raised">Update Details</button>
                    <br/><br/>
                </form>
              </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header card-header-icon" data-background-color="rose">
              <i class="mdi mdi-lock"></i>
            </div>
            <div class="card-content">
              <h4 class="card-title">Change Your Password</h4>
              <form action="/update/password" method="post" >
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="mdi mdi-lock"></i>
                    </span>
                    <div class="form-group label-floating is-empty">
                      <label class="control-label">Current Password</label>
                      <input type="password" name="old_password" class="form-control" style="cursor: auto;">
                      <span class="material-input"></span></div>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="mdi mdi-lock"></i>
                    </span>
                    <div class="form-group label-floating is-empty">
                      <label class="control-label">New Password</label>
                      <input type="password" name="new_password" class="form-control" style="cursor: auto;">
                      <span class="material-input"></span></div>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="mdi mdi-lock"></i>
                    </span>
                    <div class="form-group label-floating is-empty">
                      <label class="control-label">Re-Enter Password</label>
                      <input type="password"  name="repeat_password" class="form-control" style="cursor: auto;">
                      <span class="material-input"></span></div>
                  </div>
                  <csrf />
                  <button type="submit" class="btn btn-info btn-raised">Change Password</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import csrf from "@/components/csrf"
  export default {
    data:function () {
      return {
        user: Window.user
      }
    },
    components:{
      csrf
    }
  }
</script>

<style lang="css">
</style>
