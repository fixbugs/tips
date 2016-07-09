<div clas="container">
         <a class="btn btn-default" href="" role="button" onclick="history.go(-1)">返回上一页</a>
</div>
<div class="container">
<form name="post-form" action='<?php if($action == "edit"){ echo '/index.php/tips/edit';}else{ echo '/index.php/tips/add';} ?>' method='post'>
<?php if($data['tips_id']){ ?>
<input type='hidden' name='tips_id' value='<?php if($data["tips_id"]){ echo $data["tips_id"];}?>'>
<?php } ?>
          <div class="form-group">
          <label for="tipid">ID:</label>
          <input type="text" class="form-control" id="tipid" placeholder="Tip ID" value="<?php echo $data['tips_id'];?>" disabled >
          </div>
          <div class="form-group">
          <label for="message">提示内容：</label>
          <input type="text" class="form-control" id="message" placeholder="Message" name='message' value="<?php if($data["tips_message"]){ echo $data["tips_message"];}?>">
          </div>
          <div class="form-group">
          <label for="parent_id">父ID：</label>
          <input type="text" class="form-control" id="parent_id" placeholder="Parent ID" name='parent_id' value="<?php if($data["parent_id"]){ echo $data["parent_id"];}?>">
          </div>
          <button type="submit" class="btn btn-default">Edit Tips</button>
          <button type="reset" class="btn btn-default">Reset</button>
</form>
<script src="/static/js/common.js"></script>
</div>