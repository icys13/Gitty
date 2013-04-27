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
		<label class="icon-search" for="repos-filter"></label>
		<input class="filter-input" id="repos-filter" placeholder="查找项目" type="text">
		<a class="btn btn-new-repo" href="<?=base_url()?>index.php/ordinary/index/create_repo">
			<i class="icon"></i>
				创建
		</a>
	</div>
	<div class="tabs-content">
		<div class="tab-pane" id="creates"></div>
		<div class="tab-pane" id="forks"></div>
		<div class="tab-pane" id="participates"></div>
	</div>
</div>
