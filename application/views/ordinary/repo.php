<div class="main">
	<nav class="page-nav">
		<ul class="cf">
			<li>
				<a href="">项目</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="">动态</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="">关系圈</a>
			</li>
		</ul>
	</nav>
	<ul class="tabs">
		<li class="active">
			<a data-toggle="tab" href="#creates">创建(<?=$creates['count']?>)</a>
		</li>
		<li>
			<a data-toggle="tab" href="#forks">克隆(<?=$forks['count']?>)
		</li>
		<li>
			<a data-toggle="tab" href="#participates">参与(<?=$participates['count']?>)</a>
		</li>
	</ul>
	<div class="profile-actions">
		<a class="btn btn-new-repo" href="<?=base_url()?>index.php/ordinary/index/create_repo">
			<i class="icon"></i>
				创建
		</a>
	</div>
	<div class="tabs-content">
		<div class="tab-pane active" id="creates"><?=$creates['data']?></div>
		<div class="tab-pane" id="forks"><?=$forks['data']?></div>
		<div class="tab-pane" id="participates"><?=$participates['data']?></div>
	</div>
</div>
