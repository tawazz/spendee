<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="col-md-4">
            <img src="/images/user.png" class="img-rounded img-responsive" />
            <br/>
            <br/>
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
        <div class="col-md-8">
            <div class="form-group col-md-8">
                <form action="/update/password" method="post" >
                    <h3>Change Your Password</h3>
                    <br />
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
