<div class="settings-main">
	<h3><?php if(isset($error)) echo $error;?></h3>
	<div class="fl">
		<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/account" class="form-horizontal" method="post">
			<div class="control-group">
				<label class="control-label" for="full_name">用户全名</label>
				<div class="controls">
					<input class="input-large" id="full_name" name="full_name" value="<?=$full_name?>" size="30" type="text">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="location">位置</label>
				<div class="controls">
					<input class="input-large" id="location" name="location" value="<?=$location?>" size="30" type="text">
				</div>
			</div>
			<div class="form-actions">
				<input class="btn btn-large btn-primary" name="submit" type="submit" value="更新">
			</div>
		</form>
	</div>
</div>
</div>
</div>
