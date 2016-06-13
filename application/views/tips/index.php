<div class="row">
          <div class="col-sm-9">
          <a class="btn btn-default" href="/index.php/tips/add" role="button">添加</a>
          <a class="btn btn-default" href="#" role="button">删除</a>
          </div>
</div>

<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th>父ID</th>
        <th>提示内容</th>
        <th>编辑者</th>
        <th>创建时间</th>
        <th>编辑时间</th>
        <th>状态</th>
    </tr>
</thead>
<tbody>
<?php foreach($tips as $tip){ ?>
<tr>
    <td>
    <?php echo $tip['tips_id'];?>
    </td>
    <td>
    <?php echo $tip['parent_id'];?>
    </td>
    <td>
    <?php echo $tip['tips_message'];?>
    </td>
    <td>
    <?php echo $tip['user_id'];?>
    </td>
    <td>
    <?php echo convert_time_to_zh($tip['create_time']);?>
    </td>
    <td>
    <?php echo $tip['edit_time'];?>
    </td>
    <td>
    <?php echo $tip['status'];?>
    </td>
</tr>
<?php } ?>
</tbody>
<thead>
    <tr>
        <th>ID</th>
        <th>父ID</th>
        <th>提示内容</th>
        <th>编辑者</th>
        <th>创建时间</th>
        <th>编辑时间</th>
        <th>状态</th>
    </tr>
 </thead>
</table>
