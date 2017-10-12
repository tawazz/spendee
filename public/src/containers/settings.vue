<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="col-md-4">
            <img src="/images/user.png" class="img-rounded img-responsive" />
            <br/>
            <br/>
            <form action="/update/user" method="post" enctype="multipart/form-data">
                <label>Registered Username</label>
                <input type="text" class="form-control" v-model="user.name" disabled>
                <label>Registered Email</label>
                <input type="email" class="form-control" v-model="user.email"  placeholder="{{user.email}}" name="email">
                <br>
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
                    <label>Enter Old Password</label>
                    <input type="password" class="form-control" name="old_password">
                    <label>Enter New Password</label>
                    <input type="password" class="form-control"  name="new_password">
                    <label>Confirm New Password</label>
                    <input type="password" class="form-control"  name="repeat_password" />
                    <br>
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
    }
    components:{
      csrf
    }
  }
</script>

<style lang="css">
</style>
