<div class="container">
<form name="post-form" action='/index.php/login/index' method='post'>
<input type="hidden" name="return_url" value="<?php if(isset($return_url)){ echo $return_url;} ?>" >
    <div class="form-group">
            <label for="username">用户名：</label>
            <input type="text" class="form-control" id="username" placeholder="User Name" name='username'>
    </div>
    <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name='password'>
    </div>
            <button type="submit" class="btn btn-default">Sign in</button>
            <button type="reset" class="btn btn-default">Reset</button>
</form>
<script src="/static/js/common.js"></script>
</div>