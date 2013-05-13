<div class="settings-main">
	<h3><?php if(isset($error)) echo $error;?></h3>
	<div class="manage-email">
		<div class="using-email">
			<h3>你正在使用:</h3>
			<div class="box">
				<i class="icon-ok fr"></i>
					<?=$data['email']?>
			</div>
		</div>
		<div class="change-email">
			<h3>更改邮箱地址:</h3>
			<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/email" class="form-horizontal" method="post">
				<div class="control-group">
					<label class="control-label" for="email">新的邮箱地址</label>
					<div class="controls">
						<input id="email" name="email" size="30" type="email">
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
</div>
