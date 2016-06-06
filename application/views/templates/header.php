<!DOCTYPE html>
<html lang="zh-CN">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if($title){ ?>
         <title><?php echo $title;?></title>
    <?php }else {?>
        <title>CodeIgniter Tutorial</title>
    <?php }?>
    <!-- <link rel="stylesheet" type="text/css" href="/static/css/base.css" /> -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" />
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>

<h1><?php echo $title; ?></h1>