<div class="commit">
	<div class="fr">
		<span class="time">
			<i class="icon-time"></i>
			<time><?=date("Y-m-d",strtotime($date))?></time>
		</span>
	</div>
	<a href="<?=base_url()?>index.php/ordinary/index/user/<?=$username?>"><?=$username?></a> : 
	<span class="commit-message"><?=$message?></span>
	<div class="commit-meta">
	<a href="<?=base_url()?>index.php/ordinary/tree/index/<?=$username?>/<?=$repo_name?>/<?=$commit?>/<?=$repo_name?>?home=true" class="browse-code">浏览代码 》</a>
		<span class="sha"><?=$commit?></span>
		parent : 
		<a href="<?=base_url()?>index.php/ordinary/commit/detail/<?=$username?>/<?=$repo_name?>/<?=$parent?>" class="sha"><?=substr($parent,0,8)?></a>
	</div>
</div>
