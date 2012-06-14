<div class="title">
	<h1>ABOUT CC:<br/><?=$title?></h1>
</div>
	
	<h3>The Report</h3>
	<?=$node->field_the_report[0]['view']?>
	
	<h3>Editors</h3>
	<?php
		foreach($node->field_editors as $editor) {
			echo '<h4>' . $editor['editor_name'] . '</h4>';
			echo $editor['editor_description'];
		}
	?>
	
	<h3>Contributors</h3>
	<?=$node->field_contributors[0]['view']?>
	
	<h3>Readership</h3>
	<?=$node->field_readership[0]['view']?>
	
</div>