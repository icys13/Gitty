<h3><?php if(isset($error)) echo $error?></h3>
<div class="module">
<form accetp-charset="UTF-8" action="<?=base_url()?>index.php/base/regist" class="form-page form-horizontal" id="new_user" method="post">
			<div class="control-group">
				<label class="control-label" for="user_email">邮箱</label>
				<div class="controls">
					<input autofocus="autofocus" class="input-large" id="user_email" name="email" size="30" type="text">
				</div>
			</div>	
			<div class="control-group">
				<label class="control-label" for="user_username">用户名</label>
				<div clsss="controls">
					<input class="input-large" id="user_username" name="username" size="30" type="text">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="user_password">密码</label>
				<div class="controls">
					<input autofocus="autofocus" class="input-large" id="user_password" name="password" size="30" type="password">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="user_password_confirmation">密码确认</label>
				<div class="controls">
					<input class="input-large" for="user_password_confirmation" name="confirm" size="30" type="password">
				</div>
			</div>
			<div class="form-actions">
				<input class="btn btn-large btn primary" name="commit" type="submit" value="注册">
				<p class="help-block">已有账户?<a href="">登入</a></p>
			</div>	
		</form>
	</div>
</div>
