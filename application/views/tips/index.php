
<?php foreach($tips as $tip){ ?>
<ul>
<li><?php echo $tip['tips_id'];?></li>
<li><?php echo $tip['parent_id'];?></li>
<li><?php echo $tip['tips_message'];?></li>
<li><?php echo $tip['user_id'];?></li>
<li><?php echo convert_time_to_zh($tip['create_time']);?></li>
<li><?php echo $tip['edit_time'];?></li>
<li><?php echo $tip['status'];?></li>
</ul>
<?php } ?>