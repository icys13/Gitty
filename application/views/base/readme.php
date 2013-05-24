<div class="repo-init module">
	<div class="repo-init-content module-bd">
<?php
echo '<span style="color:red">'.$error.'</span>';
if(isset($cover))
{
	if($cover)
	{
		echo '<input class="btn btn-large btn-primary" type="button" value="确定" onclick="window.location=\''.site_url('base/install/action').'\'"';
	}
}
if(isset($link))
	echo '<h3>'.$link.'</h3>';
?>
</div>
</div>
