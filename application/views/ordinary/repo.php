<div class="main">
	<ul class="tabs">
		<li class="">
			<a data-toggle="tab" href="#creates">创建</a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#forks">克隆</a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#participates">参与</a>
		</li>
	</ul>
	<div class="profile-actions">
		<a class="btn btn-new-repo" href="<?=base_url()?>index.php/ordinary/index/create_repo">
			<i class="icon"></i>
				创建
		</a>
	</div>
	<div class="tabs-content">
		<div id="creates"><?=$creates?></div>
		<div id="forks"><?=$forks?></div>
		<div id="participates"><?=$participates?></div>
	</div>
</div>
