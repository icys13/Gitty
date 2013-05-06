<header class="title-bar cf s2">
	<div class="fl">
		<h1>
		<a href=""><?=$username?></a>
		<strong><a href=""><?=$reponame?></a></strong>
		</h1>
	</div>
	<div class="fr">
</header>
<div class="repo-desc-clone cf s2">
	<div class="repo-desc">
		<span title=""></span>
	</div>
	<a class="btn-download" href=""><i class="icon"></i>下载(.zip)</a>
	<a class="btn-clone" href="#"><i class="icon arr"></i><i class="icon"></i>抓取</a>
</div>
<div class="code-browser-wrapper module" id="code">
	<div class="code-browser">
		<div class="tree-browser">
			<ul class="js-pjax">
<?php
if(!empty($trees))
{
	$size = count($trees);
	for($i = 0;$i < $size;$i++)
		echo '<li><i class="icon-folder"></i><a href="">'.$trees[$i].'</a></li>';
}
if(!empty($blobs))
{
	$size = count($blobs);
	for($i = 0;$i < $size;$i++)
		echo '<li><i class="icon-folder"></i><a href="">'.$blobs[$i].'</a></li>';
}
?>
			</ul>
		</div>
	</div>
