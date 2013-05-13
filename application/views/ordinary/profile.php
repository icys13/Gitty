<aside class="aside">
	<div class="dashboard">
		<header class="cf">
			<?php if($flag) { ?>
			<a href="<?=base_url()?>index.php/ordinary/index"><img alt="<?=$username?>" class="gravatar" height="32" width="32" src="<?=base_url()?>index.php/ordinary/index/get_img?username=<?=$username?>"> </a>
			<?php } else {?>
			<a href="<?=base_url()?>index.php/ordinary/index/user/<?=$username?>"><img alt="<?=$username?>" class="gravatar" height="32" width="32" src="<?=base_url()?>index.php/ordinary/index/get_img?username=<?=$username?>"></a>
			<?php }?>
			<div class="username"><?=$username?></div>
		</header>
		<div class="info">
			<ul class="stats cf">
				<li><a href=""><strong>0</strong>公开项目</a></li>
				<li><a href=""><strong>0</strong>粉丝</a></li>
				<li><a href=""><strong>0</strong>关注</a></li>
			</ul>
			<div class="gitcoin-stats not-enough">
				<ul>
					<li><span class="fr">0 MB</span><i class="icon-disk"></i>托管空间</li>
					<li><span class="fr">x 0</span><i class="icon-lock"></i>私有项目</li>
					<li><span class="fr">x 0</span><i class="icon-user-group"></i>私有项目协作人员</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="your-repos"></div>
</aside>
