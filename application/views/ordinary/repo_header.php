<header class="title-bar cf s2">
	<div class="fl">
		<h1>
		<a href=""><?=$username?></a> /
		<strong><a href=""><?=$repo_name?></a></strong>
		</h1>
	</div>
	<div class="fr">
</header>
<div class="repo-desc-clone cf s2">
	<div class="repo-desc">
		<span title=""></span>
	</div>
	<a class="btn-download" href="<?=base_url()?>index.php/ordinary/repository/download/<?=$username?>/<?=$repo_name?>/<?=$SHA?>"><i class="icon"></i>下载(.tar.gz)</a>
	<a class="btn-clone" href="<?=base_url()?>index.php/ordinary/repository/fork/<?=$username?>/<?=$repo_name?>"><i class="icon arr"></i><i class="icon"></i>派生</a>
</div>
<div class="sub-nav">
	<div class="cf">
		<ul class="tabs">
		<li class><a href="<?=base_url()?>index.php/ordinary/repository/index/<?=$username?>/<?=$repo_name?>">文件</a></li>	
		<li class><a href="<?=base_url()?>index.php/ordinary/commit/index/<?=$username?>/<?=$repo_name?>">提交历史</a></li>
		</ul>
	</div>
</div>
