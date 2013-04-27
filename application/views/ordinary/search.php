<div class="global-search">
	<form accept-charset="UTF-8" action="<?=base_url()?>index.php/ordinary/index/search" id="search_form" method="post">
		<input class="keyword" id="keyword" name="keyword" palceholder="搜索..." size="60" type="text" value="<?php if(isset($value)) echo $value;?>">
		<button class="btn">
			<span class="icon-search">q</span>
		</button>
	</form>
</div>
