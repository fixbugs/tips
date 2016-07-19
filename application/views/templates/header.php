<!DOCTYPE html>
<html lang="zh-CN">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if( isset($title) ){ ?>
         <title><?php echo $title;?></title>
    <?php }else {?>
        <title>Goitt Tips</title>
    <?php }?>
    <!-- <link rel="stylesheet" type="text/css" href="/static/css/base.css" /> -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" />
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>
<nav class="navbar navbar-fixed-top">
    <div class="container">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              </button>
              <a class="logo" href="/"></a>
              </div>
              <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
              <li><a href="/">Home</a></li>
              <li><a href="/index.php/tips/index">Tips</a></li>
              <li><a href="/index.php/user/index">User List</a></li>
              <li><a href="/index.php/welcome/features">Features</a></li>
              <li><a class="loginbtn" href="/index.php/login/index">Login</a></li>
              <li><a class="loginbtn" href="/index.php/login/index" data-toggle="modal" data-target="#loginOut" >Login Out</a></li>
              <!-- <li><a id="signup-header-btn" class="btn btn-warning signupbtn" href="#" data-toggle="modal" data-target="#loginModal" data-action="signup-form">Sign Up</a></li> -->

              </ul>
          </div>
    </div>
</nav>

<div style="margin-top:50px">
<? if(isset($title)){ ?>
<div class="container">
              <? if(isset($title)){ ?>
                   <h1><?php if(isset($title)){ echo $title;} ?></h1>
              <? } ?>
</div>
<? } ?>