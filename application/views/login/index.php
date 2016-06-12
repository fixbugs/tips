
<form name="post-form" action='/index.php/login/index' method='post'>
    <div class="form-group">
            <label for="username">用户名：</label>
            <input type="text" class="form-control" id="username" placeholder="User Name" name='username'>
    </div>
    <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name='password'>
    </div>
            <button type="submit" class="btn btn-default">Sign in</button>
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