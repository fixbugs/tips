<div>
<?php foreach($users as $user){ ?>
<ul>
<li><?php echo $user['user_id'];?></li>
<li><?php echo $user['password'];?></li>
<li><?php echo $user['truename'];?></li>
<li><?php echo $user['email'];?></li>
<li><?php echo convert_time_to_zh($user['create_time']);?></li>
<li><?php echo convert_time_to_zh($user['update_time']);?></li>
</ul>
<?php } ?>
</div>
<div>
<a href = '/index.php/user/add'>添加</a>
<a href = ''>删除</a>
</div>