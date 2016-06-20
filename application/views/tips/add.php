<div class="container">
<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/tips/edit';}else{ echo '/index.php/tips/add';} ?>' method='post'>
          <div class="form-group">
          <label for="message">提示内容：</label>
          <input type="text" class="form-control" id="message" placeholder="Message" name='message'>
          </div>
          <div class="form-group">
          <label for="parent_id">父ID：</label>
          <input type="text" class="form-control" id="parent_id" placeholder="Parent ID" name='parent_id'>
          </div>
          <button type="submit" class="btn btn-default">Add Tips</button>
          <button type="reset" class="btn btn-default">Reset</button>
</form>
<script src="/static/js/common.js"></script>
</div>