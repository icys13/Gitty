<h3><?php if(isset($error)) echo $error?></h3>
<div class="module">
	<form accept-charset="UTF-8" action="<?=base_url()?>index.php/base/signup" class="form-page form-horizontal" method="post">
		<div class="control-group">
			<label class="control-label" for="username">用户名</label>
			<div class="controls">
				<input class="input-large" id="username" name="username" size="30" type="text">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">密码</label>
			<div class="controls">
				<input class="input-large" id="password" name="password" size="30" type="password">
			</div>
		</div>
		<div class="form-actions">
			<input class="btn btn-large btn-primary" name="submit" type="submit" value="登录">
		</div>
	</form>
</div>
