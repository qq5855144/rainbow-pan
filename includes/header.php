<?php
@header('Content-Type: text/html; charset=UTF-8');
$header_manager_authorized = is_frontend_manager();
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']?>">
  <meta name="description" content="<?php echo $conf['description']?>">
  <!-- Mobile support -->
  <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="format-detection" content="telephone=no">
  <link rel="dns-prefetch" href="//s4.zstatic.net">
  <link rel="preconnect" href="https://s4.zstatic.net" crossorigin>
  <link rel="dns-prefetch" href="//api.qrserver.com">
  <!-- Bootstrap Material Design -->
  <link href="https://s4.zstatic.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://s4.zstatic.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css" rel="stylesheet">
  <link href="https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/css/ripples.min.css" rel="stylesheet">
  <?php if(!empty($is_file) && isset($filetype) && $filetype==2){?><link rel="stylesheet" href="https://s4.zstatic.net/ajax/libs/aplayer/1.10.1/APlayer.min.css"><?php }?>
  <?php if(!empty($is_file) && isset($filetype) && $filetype==3){?><link href="assets/css/ckplayer.css" rel="stylesheet"><?php }?>
  <link href="assets/css/style.css?v=<?php echo VERSION?>" rel="stylesheet">
  <!--[if lt IE 9]>
    <script src="https://s4.zstatic.net/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://s4.zstatic.net/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

  <div class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><?php echo $conf['title']?></a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li class="<?php echo checkIfActive('index,')?>"><a href="./"><i class="fa fa-list" aria-hidden="true"></i> 文件列表</a></li>
            <li class="<?php echo checkIfActive('upload')?>"><a href="./upload.php"><i class="fa fa-upload" aria-hidden="true"></i> 上传文件</a></li>
            <?php if($is_file){?>
            <li class="<?php echo checkIfActive('file')?>"><a href=""><i class="fa fa-file" aria-hidden="true"></i> 文件查看</a></li>
            <?php }?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="<?php echo checkIfActive('mine')?>"><a href="./?m=mine"><i class="fa fa-folder-open" aria-hidden="true"></i> 我的文件</a></li>
            <?php if($header_manager_authorized){?>
            <li class="dropdown">
              <a data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check-circle" aria-hidden="true"></i> 站长模式<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#" id="managerLogout"><i class="fa fa-lock" aria-hidden="true"></i> 退出站长模式</a></li>
              </ul>
            </li>
            <?php }else{?>
            <li><a href="#" id="managerAuthOpen"><i class="fa fa-lock" aria-hidden="true"></i> 站长授权</a></li>
            <?php }?>
            <?php if($conf['userlogin']){?>
              <?php if($islogin2){?>
              <li class="dropdown">
                <a data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $userrow['type']=='qq'?'qq':'wechat';?>" aria-hidden="true"></i> <?php echo $userrow['nickname']?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="./login.php?logout=1" onclick="return confirm('是否确定退出登录？')"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出登录</a></li>
                </ul>
              </li>
              <?php }else{?>
              <li class="<?php echo checkIfActive('login')?>"><a href="./login.php"><i class="fa fa-user-circle" aria-hidden="true"></i> 未登录</a></li>
              <?php }?>
            <?php }?>
          </ul>
      </div>
    </div>
  </div>
