<div class="container">
      <form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/tag/edit';}else{ echo '/index.php/tag/add';} ?>' method='post'>
          <input type='hidden' name='tag_id' value="<?php echo $data['tag_id'];?>">
          <div class="form-group">
          <label for="tagname">标签名：</label>
          <input type="text" class="form-control" id="tagname" placeholder="Tag Name" name='tag_name' value="<?php echo $data['tag_name'];?>">
          </div>
          <div class="form-group">
          <label for="tagtype">标签类型：</label>
          <input type="text" class="form-control" id="tagtype" placeholder="Tag Type" name='tag_type' value="<?php echo $data['tag_type'];?>">
          </div>
          <button type="submit" class="btn btn-default">Edit Tag</button>
          <button type="reset" class="btn btn-default">Reset</button>
      </form>
          <script src="/static/js/common.js"></script>
</div>