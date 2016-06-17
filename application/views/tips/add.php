<div class="container">
<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/tips/edit';}else{ echo '/index.php/tips/add';} ?>' method='post'>
          <div class="form-group">
          <label for="message">提示内容：</label>
          <input type="text" class="form-control" id="message" placeholder="Message" name='message'>
          </div>
          <div class="form-group">
          <label for="parent_id">父ID：</label>
          <input type="text" class="form-control" id="parent_id" placeholder="Parent ID" name='parent_id'>
          </div>
          <button type="submit" class="btn btn-default">Add Message</button>
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