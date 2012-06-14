<?php $GLOBALS['id'] = 0; ?>
<div class="nid" id="<?=$node->nid?>">
	<div class="title">
		<h1><?php echo $title; if($teaser) { echo ' - ' . $node->field_released[0]['value']; } ?></h1>
		<?php if(!$teaser) {?>
			<span id="by"><?=$node->field_released[0]['value']?></span>
		<?php } ?>
	</div>

	<div class="forward clearfix">
		<?php echo apply_media($node, $node->field_forward[0]['value']); ?>
	</div>

	<div class="magazine-links">
		<ul>
			<li><a href="#" class="share">Share</a></li>
			<li><a href="#">Buy <?=$title?></a></li>
			<li><?='<a href="/by-excerpt/' . $title . '" title="' . $title . '" class="category">'?>View Articles</a></li>
			<?php
				if(user_access('administer') || in_array("editor", array_values($user->roles))) { ?>
				<li><?='<a href="/magazine/export/' . $title . '" title="' . $title . ' Export" class="category">'?>Export <?=$title?> Data</a></li>	
				<?php } ?>
		</ul>
	
		<?php include 'inc/share.php'; ?>
	</div>
</div> <!--/node-->

<?php $GLOBALS['id'] = 0; ?>