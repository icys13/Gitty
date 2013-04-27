<div class="aside">
	<div class="profile">
		<div class="actions">
			<span class="is-you">这是您本人!</span>
			<a href="<?=base_url()?>index.php/ordinary/index/profile">
				<i class="icon-edit"></i>
				修改档案
			</a>
		</div>
		<div class="info">
			<h1>
				<a href="">
					<img class="gravatar" height="64" width="64" src="<?=base_url()?>index.php/ordinary/index/get_img?username=<?=$username?>">
				</a>
				<span><?=$username?></span>
				<em>(<?=$full_name?>)</em>
			</h1>
			<div class="vcard">
				<dl>
					<dt>地理位置</dt>
					<dd><?=$location?></dd>
					<dt>加入时间</dt>
					<dd><?=$date?></dd>
				</dl>
			</div>
			<ul class="stats cf">
				<li>
					<a href=""><strong>0</strong>项目</a>
				</li>
				<li>
					<a href=""><strong>0</strong>粉丝</a>
				</li>
				<li>
					<a href=""><strong>0</strong>关注</a>
				</li>
			</ul>
		</div>
	</div>
</div>
