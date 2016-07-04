<div class="container">
    <div class="row">
          <div class="col-sm-9">
          <a class="btn btn-default" href="/index.php/tag/add" role="button">添加</a>
          <a class="btn btn-default" href="#" role="button">删除</a>
     </div>
</div>

<div class="container">

<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th>标签名称</th>
        <th>标签类型</th>
        <th>创建时间</th>
        <th>创建者ID</th>
    </tr>
</thead>
<tbody>
<?php if(!$tags){ ?>
<tr>
<td>
暂时没有数据
</td>
</tr>
<?php } ?>
<?php foreach($tags as $tag){ ?>
<tr>
    <td>
    <?php echo $tag['tag_id'];?>
    </td>
    <td>
    <?php echo $tag['tag_name'];?>
    </td>
    <td>
    <?php echo $tag['tag_type'];?>
    </td>
    <td>
    <?php echo convert_time_to_zh($tip['create_time']);?>
    </td>
    <td>
    <?php echo $tag['user_id'];?>
    </td>
</tr>
<?php } ?>
</tbody>
<thead>
    <tr>
        <th>ID</th>
        <th>标签名称</th>
        <th>标签类型</th>
        <th>创建时间</th>
        <th>创建者ID</th>
    </tr>
 </thead>
</table>
</div>