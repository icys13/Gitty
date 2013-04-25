<h3><?php if(isset($error)) echo $error?></h3>
<div class="module">
	<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/email" class="form-page form-horizontal" method="post">
		<div class="control-group">
			<label class="control-label" for="old_email">你正在使用:</label>
			<p><?=$data['email']?></p>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">新邮箱</label>
			<div class="controls">
				<input class="input-large" id="email" name="email" size="30" type="text">
			</div>
		</div>
		<div class="form-actions">
			<input class="btn btn-large btn-primary" name="submit" type="submit" value="更新">
		</div>
	</form>
</div>
