<form action='<?php if($action == "edit"){ echo '/index.php/tips/edit';}else{ echo '/index.php/tips/add';} ?>' method='post'>
<?php if($data['tips_id']){ ?>
<input type='hiddena' name='tips_id' value='<?php if($data["tips_id"]){ echo $data["tips_id"];}?>'>
<?php } ?>
<ul>
     <li><input type='text' name='message' value='<?php if($data["tips_message"]){ echo $data["tips_message"];}?>'></li>
     <li><input type='text' name='parent_id' value='<?php if($data["parent_id"]){ echo $data["parent_id"];}?>'></li>
     <li><button type='submit' value='Submit'>Submit</button></li>
     <li><button type='reset' value='Reset'>Reset</button></li>
</ul>
</form>