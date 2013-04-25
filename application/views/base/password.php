<h3><?php if(isset($error)) echo $error?></h3>
<div class="module">
	<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/password" class="form-page form-horizontal" method="post">
		<div class="control-group">
			<label class="control-label" for="old_pwd">旧密码</label>
			<div class="controls">
				<input class="input-large" id="old_pwd" name="old_pwd" size="30" type="password">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="new_pwd">新密码</label>
			<div class="controls">
				<input class="input-large" id="new_pwd" name="new_pwd" size="30" type="password">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="con_pwd">确认密码</label>
			<div class="controls">
				<input class="input-large" id="con_pwd" name="con_pwd" size="30" type="password">
			</div>
		</div>
		<div class="form-actions">
			<input class="btn btn-large btn-primary" name="submit" type="submit" value="更新">
		</div>
	</form>
</div>
