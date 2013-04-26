<div class="module settings-content">
	<div class="module-bd settings-inner cf">
		<div class="settings-main">
			<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index'?>/new_key" class="form-horizontal" id="new_key" name="new_key" method="post">
			<div class="controls-group">
				<label class="control-label" for="username">用户名</label>
				<div class="controls">
					<input class="input-large" id="username" name="username" value="<?=$username?>" size="30" type="text" disabled="disabled">
				</div>
			</div>
			<div class="controls-group">
				<label class="control-label" for="ssh_key">公钥</div>
				<div class="controls">
					<textarea class="input-large" id="ssh_key" name="ssh_key" cols="47" rows="12"></textarea>
				</div>
			</div>
			<div class="form-actions">
				<input class="btn btn-large btn-primary" name="submit" type="submit" value="添加">
			</div>
			</form>
		</div>
	</div>
</div>
