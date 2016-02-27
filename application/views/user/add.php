<form action='<?php if($action == "edit"){ echo '/index.php/user/edit';}else{ echo '/index.php/user/add';} ?>' method='post'>
<ul>
     <li><span>用户名:</span><input type='text' name='username' value='<?php if(isset($data) ){ echo $data["username"];} ?>'></li>
     <li><span>密码:</span><input type='password' name='password' value='<?php if(isset($data) ){ echo $data["password"];} ?>'></li>
     <li><span>密码重复:</span><input type='password' name='re_password' value=''></li>
     <li><span>真实姓名:</span><input type='text' name='truename' value='<?php if(isset($data) ){ echo $data["truename"];} ?>'></li>
     <li><span>E-mail:</span><input type='text' name='email' value='<?php if(isset($data) ){ echo $data["email"];} ?>'></li>
     <li><button type='submit' value='Submit'>Submit</button></li>
     <li><button type='reset' value='Reset'>Reset</button></li>
</ul>
</form>