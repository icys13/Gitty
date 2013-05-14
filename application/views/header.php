<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<title><?=$title?></title>
		<meta content="width=device-width" name="viewpost">
		<link href="<?=base_url()?>assets/css/gitcafe.css" media="screen" rel="stylesheet" type="text/css">
		<!-- [if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body>
		<header id="header" role="banner">
			<div class="inner cf">
				<a href="<?=base_url()?>" class="logo">
					<img alt="Gitty" height="86" width="130"src="./assets/logo.png">
				</a>
				<span class="slogan">Enjoy your talent coding ^_^</span>
				<nav class="top-nav">
					<ul>
						<li><a href="">探索</a></li>
						<li><a href="">博客</a></li>
						<li><a href="">帮助</a></li>
						<li><a href="">反馈</a></li>
						<li><a href="">关于</a></li>
					</ul>
				</nav>
				<div class="user-nav">
					<form accept-charset="utf-8" action="<?=base_url()?>index.php/ordinary/index/search" id="search-form" method="post">
						<div style="margin:0;padding:0;display:inline">
							<input name="utf8" type="hidden">
						</div>
						<input id="keyword" name="keyword" placeholder="搜索 ..." type=text">
						<button class="icon-search" type="submit">q</button>
					</form>
					<ul class="dropdown" id="user">
						<li><a href=""><img alt="<?=$username?>" class="gravatar" height="16" src="<?=base_url()?>index.php/ordinary/index/get_img?username=<?=$username?>"></a></li>
						<li>
							<a class="dropdown-toggle" href="<?=base_url()?>index.php/ordinary/index/user/<?=$username?>"><?=$username?><i class="arrow"></i></a>
							<ul class="dropdown-menu">
								<li>
									<a class="menu-profile" href="<?=base_url()?>index.php/ordinary/index">
										<i class="icon-profile"></i>个人主页
									</a>
								</li>
								<li>
									<a href="<?=base_url()?>index.php/ordinary/index/account">
										<i class="icon-settings-alt"></i>账户设置
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?=base_url()?>index.php/ordinary/index/create_repo">
										<i class="icon-new-repo"></i>创建项目
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?=base_url()?>index.php/ordinary/index/signout">
										<i class="icon-signout"></i>
										登出
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</header>
		<div id="content" role="main">
			<div class="inner cf">
				<!-- [if lt IE 9]><p class="chromeframe alert alert-error"><i class="icon-exclamation-sign"></i>我们强烈建议您<a href="http://browsehappy.com">升级浏览器</a>,
				或<a href="http://www.google.com/chrome/chromeframe">安装 Google Chrome 浏览器内嵌框架</a>。</a><![endif]-->
