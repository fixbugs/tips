<div class="container">
<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/user/edit';}else{ echo '/index.php/user/add';} ?>' method='post'>
<?php if(isset($data) ){ ?>
<input type='hidden' name='user_id' value='<?php echo $data["user_id"]; ?>'>
<?php } ?>
          <div class="form-group">
          <label for="userid">ID：</label>
          <input type="text" class="form-control" id="userid" placeholder="User Id" value="<?php if($data["user_id"]){ echo $data["user_id"];}?>" disabled >
          </div>
          <div class="form-group">
          <label for="username">用户名：</label>
          <input type="text" class="form-control" id="username" placeholder="User Name" name='username' value="<?php if($data["username"]){ echo $data["username"];}?>">
          </div>
          <div class="form-group">
          <label for="password">密码：</label>
          <input type="password" class="form-control" id="password" placeholder="Password" name='password' value="<?php if($data["password"]){ echo $data["password"];}?>">
          </div>
          <div class="form-group">
          <label for="re_password">密码重复：</label>
          <input type="password" class="form-control" id="re_password" placeholder="Repeat Password" name='re_password' value="">
          </div>
          <div class="form-group">
          <label for="truename">真实姓名：</label>
          <input type="text" class="form-control" id="truename" placeholder="True Name" name='truename' value="<?php if($data["truename"]){ echo $data["truename"];}?>">
          </div>
          <div class="form-group">
          <label for="email">E-mail：</label>
          <input type="text" class="form-control" id="email" placeholder="E-mail" name='email' value="<?php if($data["email"]){ echo $data["email"];}?>">
          </div>
          <button type="submit" class="btn btn-default">Edit User</button>
          <button type="reset" class="btn btn-default">Reset</button>
</form>
<script src="/static/js/common.js"></script>
</div>