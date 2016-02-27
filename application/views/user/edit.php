<form action='<?php if($action == "edit"){ echo '/index.php/user/edit';}else{ echo '/index.php/user/add';} ?>' method='post'>
<?php if(isset($data) ){ ?>
<input type='hidden' name='user_id' value='<?php echo $data["user_id"]; ?>'>
<?php } ?>
<ul>
     <li><input type='text' name='username' value='<?php if(isset($data) ){ echo $data["username"];} ?>'></li>
     <li><input type='password' name='password' value='<?php if(isset($data) ){ echo $data["password"];} ?>'></li>
     <li><input type='password' name='re_password' value=''></li>
     <li><input type='text' name='truename' value='<?php if(isset($data) ){ echo $data["truename"];} ?>'></li>
     <li><input type='text' name='email' value='<?php if(isset($data) ){ echo $data["email"];} ?>'></li>
     <li><button type='submit' value='Submit'>Submit</button></li>
     <li><button type='reset' value='Reset'>Reset</button></li>
</ul>
</form>