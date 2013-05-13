		<div class="settings-main">
			<div class="fl">
			<div class="controls-group">
				<label class="control-label" for="username">用户名</label>
				<div class="controls">
					<input class="input-large" id="username" name="username" value="<?=$username?>" size="30" type="text" disabled="disabled">
				</div>
			</div>
			<div class="controls-group">
				<label class="control-label" for="ssh_key">公钥</div>
				<div class="controls">
					<textarea class="input-large" id="ssh_key" name="ssh_key" cols="47" rows="12" disabled="disabled"><?=$ssh_key?></textarea>
				</div>
			</div>
			<div class="controls-group">
				<div controls="controls">
					<a class="add-key" href="<?=base_url()?>index.php<?php if($admin) echo '/admin/index'; else echo '/ordinary/index'?>/del_key/<?=$username?>">删除</a>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
