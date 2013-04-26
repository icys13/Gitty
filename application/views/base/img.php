<h3><?php if(isset($error)) echo $error?></h3>
<div class="module">
	<form accept-charset="UTF-8" action="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/img" class="form-page form-horizontal" enctype="multipart/form-data" method="post">
		<div class="control-group">
			<label class="control-label" for="old_img">上传头像</label>
			<div class="controls">
				<div class="fileupload">
					<div class="image-preview">
						<img src="<?=base_url()?>index.php<?php if($admin) echo '/admin/index';else echo '/ordinary/index';?>/get_img?username=<?=$username?>">
					</div>
					<span class="btn btn-fileinput">
					<span>选择文件...</span>
					<input class="input-file" id="img" name="img" type="file">
					</span>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<input class="btn btn-large btn-primary" name="submit" type="submit" value="更新">
		</div>
	</form>
</div>
