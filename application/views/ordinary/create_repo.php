<div class="module settings-content">
	<div class="module-bd settings-inner cf">
		<div class="settings-main">
			<form accept-charset="UTF-8" action="<?=base_url()?>index.php/ordinary/index/create_repo" class="form-page form-horizontal" method="post">
				<div class="controls-group">
					<label class="control-label" for="creator">用户名</label>
					<div class="controls">
						<input class="input-large" id="username" name="username" value="<?=$username?>" size="30" type="text" disabled="disabled">
						<input class="input-large" id="creator" name="creator" value="<?=$username?>" type="hidden">
					</div>
				</div>
				<div class="controls-group">
					<label class="control-label" for="repo_name">项目名</label>
					<div class="controls">
						<input class="input-large" id="repo_name" name="repo_name" size="30" type="text">
					</div>
				</div>
				<div class="controls-group">
					<label class="control-label" for="description">项目描述</label>
					<div class="controls">
						<textarea class="input-large" id="description" name="description" cols="47" rows="12"></textarea>
					</div>
				</div>
				<div class="form-actions">
					<input class="btn btn-large btn-primary" name="submit" type="submit" value="创建">
				</div>
			</div>
		</form>
	</div>
</div>
