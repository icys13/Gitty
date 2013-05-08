<ol id="toc">
<?php
	foreach($toc as $item)
	{
		echo '<li>';
		echo '<i class="icon-file"></i>';
		echo '<a href="#'.$item.'">'.$item.'</a>';
		echo '</li>';
	}
?>
</ol>
