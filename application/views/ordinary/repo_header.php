<div class="repo-image">
	<img alt="<?=$username?>" src="<?=base_url()?>assets/img/default.png">
</div>
<header class="title-bar cf s2">
	<div class="fl">
		<h1>
		<a href="<?=base_url()?>index.php/ordinary/index/user/<?=$username?>"><?=$username?></a> /
		<strong><a href="<?=base_url()?>index.php/ordinary/repository/index/<?=$username?>/<?=$repo_name?>"><?=$repo_name?></a></strong>
		</h1>
	</div>
	<div class="fr">
</header>
<div class="repo-desc-clone cf s2">
	<div class="repo-desc">
		<span title="<?=$message?>"><?=$message?></span>
	</div>
	<a class="btn-download" href="<?=base_url()?>index.php/ordinary/repository/download/<?=$username?>/<?=$repo_name?>/<?=$SHA?>"><i class="icon"></i>下载(.tar.gz)</a>
	<a class="btn-clone" href="<?=base_url()?>index.php/ordinary/repository/fork/<?=$username?>/<?=$repo_name?>"><i class="icon arr"></i><i class="icon"></i>派生</a>
</div>
<nav class="page-nav repo-nav">
	<ul class="cf">
		<li><a href="<?=base_url()?>index.php/ordinary/repository/<?=$repo_name?>">代码</a></li>
		<li class="divider"></li>
		<li><a href="">工单</a></li>
		<li class="divider"></li>
		<li><a href="">合并请求</a></li>
	</ul>
</nav>
<div class="sub-nav">
	<div class="cf">
		<ul class="tabs">
		<li class><a href="<?=base_url()?>index.php/ordinary/repository/index/<?=$username?>/<?=$repo_name?>">文件</a></li>	
		<li class><a href="<?=base_url()?>index.php/ordinary/commit/index/<?=$username?>/<?=$repo_name?>">提交历史</a></li>
		</ul>
	</div>
	<div class="branches-tags">
		<span class="branch-switcher" id="branch-switcher">
			<i class="icon-branch"></i>
			分支：
			<span>master</span>
			<i class="icon-triangle-bottom"></i>
		</span>
		<div class="module cf" id="branches-tags" style="display:none;">
			<div class="branches"></div>
			<div class="tags"></div>
		</div>
	</div>
</div>
