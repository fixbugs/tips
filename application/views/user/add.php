<form action='<?php if($action == "edit"){ echo '/index.php/user/edit';}else{ echo '/index.php/user/add';} ?>' method='post'>
<ul>
     <li><input type='text' name='username' value=''></li>
     <li><input type='password' name='password' value=''></li>
     <li><input type='password' name='re_password' value=''></li>
     <li><input type='text' name='truename' value=''></li>
     <li><input type='password' name='email' value=''></li>
     <li><button type='submit' value='Submit'>Submit</button></li>
     <li><button type='reset' value='Reset'>Reset</button></li>
</ul>
</form>