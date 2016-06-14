<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/user/edit';}else{ echo '/index.php/user/add';} ?>' method='post'>
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
          <script>
          $(document).ready(function(){
              console.log( $("form").serialize() );
          });
$("button[type=submit]").click(function(e){
    if(e && e.preventDefault){
        e.preventDefault();
    }else{
        window.event.returnValue = false;
    }
    url =  $("form[name='post-form']")[0].action;
    if(!url){
        alert('action is null');
        return;
    }
    serialize_data = $("form[name='post-form']").serialize();
    $.ajax({
            type: 'post',
     url: url,
     data: serialize_data,
     dataType: "json",
     success: function(data) {
         if(data.status){
             if(data.redirect){
                 location.href=data.redirect;
                 return;
             }else{
                 alert(data.message);
             }
         }else{
             alert(data.message);
         }
     }
     });
});
</script>