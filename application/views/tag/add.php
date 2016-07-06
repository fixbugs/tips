<div class="container">
<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/tag/edit';}else{ echo '/index.php/tag/add';} ?>' method='post'>
          <div class="form-group">
          <label for="tagname">标签名：</label>
          <input type="text" class="form-control" id="tagname" placeholder="Tag Name" name='tag_name' value="">
          </div>
          <div class="form-group">
          <label for="tagtype">标签类型：</label>
          <input type="text" class="form-control" id="tagtype" placeholder="Tag Type" name='tag_type' value="">
          </div>
           <button type="submit" class="btn btn-default">Add Tag</button>
          <button type="reset" class="btn btn-default">Reset</button>
</form>
<script src="/static/js/common.js"></script>
</div>