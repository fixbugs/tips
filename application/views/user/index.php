<div class="container">
<div class="row">
          <div class="col-sm-9">
          <a class="btn btn-default" href="/index.php/user/add" role="button">添加</a>
          </div>
</div>

<div class="container">

<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>E-mail</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
</thead>
<tbody>
<?php foreach($users as $user){ ?>
<tr>
    <td>
    <a href="/index.php/user/edit?id=<?php echo $user['user_id'];?>"><?php echo $user['user_id'];?></a>
    </td>
    <td>
    <?php echo $user['truename'];?>
    </td>
    <td>
    <?php echo $user['email'];?>
    </td>
    <td>
    <?php echo convert_time_to_zh($user['create_time']);?>
    </td>
    <td>
    <?php
if($user['update_time']){
    echo convert_time_to_zh($user['update_time']);
}else{
    echo '';
}
?>
    </td>
    <td>
<a href="/index.php/user/edit?id=<?php echo $user['user_id'];?>">编辑</a>
<a href="/index.php/user/delete?id=<?php echo $user['user_id'];?>" class="deleteBtn">删除</a>
    </td>
</tr>
<?php } ?>
</tbody>
<thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>E-mail</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
 </thead>
</table>
</div>