<div class="code-browser-wrapper module" id="code">
	<div class="breadcrumb module-hd pjax">
		<i class="icon-code"></i>
		<a href=""><?=$path?></a>
	</div>
	<div class="code-browser">
		<div class="code-browser-inner">
			<div class="tree-browser">
				<ul class="js-pjax">
<?php
if(!empty($trees))
{
	$size = count($trees);
	for($i = 0;$i < $size;$i++)
		echo '<li><i class="icon-folder"></i><a href="'.base_url().'index.php/ordinary/tree/index/'.$username.'/'.$repo_name.'/'.$trees[$i]['SHA'].'/'.$path.'/'.$trees[$i]['dir_name'].'">'.$trees[$i]['dir_name'].'</a></li>';
}
if(!empty($blobs))
{
	$size = count($blobs);
	for($i = 0;$i < $size;$i++)
		echo '<li><i class="icon-file"></i><a href="'.base_url().'index.php/ordinary/blob/index/'.$username.'/'.$repo_name.'/'.$blobs[$i]['SHA'].'/'.$path.'/'.$blobs[$i]['file_name'].'">'.$blobs[$i]['file_name'].'</a></li>';
}
?>
				</ul>
			</div>
