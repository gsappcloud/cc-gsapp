<?php
// $Id: views-view-row-node.tpl.php,v 1.3 2008/07/09 18:31:26 merlinofchaos Exp $
/**
 * @file views-view-row-node.tpl.php
 * Default simple view template to display a single node.
 *
 * Rather than doing anything with this particular template, it is more
 * efficient to use a variant of the node.tpl.php based upon the view,
 * which will be named node-view-VIEWNAME.tpl.php. This isn't actually
 * a views template, which is why it's not used here, but is a template
 * 'suggestion' given to the node template, and is used exactly
 * the same as any other variant of the node template file, such as
 * node-NODETYPE.tpl.php
 *
 * @ingroup views_templates
 */
?>

<div class="item" id="item-row-<?=$node->nid?>">
	<div class="type"><?=$node->type?>
		<div class="rollover">
		<?php
			$image = animated($node, 'image', false, null);
			echo '<img src="' . $image . '" alt="' . $title . '" width="220" height="165" />';
		?>
		</div> <!--/rollover-->
	</div>
	
	<?php
	
	if($node->type == "briefs") {
		$width = "220";
		$height = "165";
	} elseif($node->type == "notes") {
		$width = "100";
		$height = "75";
	} elseif($node->type == "papers") {
		$width = "340";
		$height = "254";
	}
	
	$image = '/sites/default/files/imagecache/' . $node->type . '-view/' . $node->type . '/'.$node->field_images[0]['filename'];
	// escape spaces
	$image = str_replace(' ', '%20', $image);
	// fix '&'
	$image = str_replace('&', '&amp;', $image);
	
	echo '<a target="_blank" href="/' . $node->path . '">';
	if($node->field_images[0]['filename']) {
		echo '<span class="'. $node->type .'"></span><img src="' . $image . '" alt="' . $title . '" width="' . $width . '" height="' . $height . '" />';
	} else {
		$image = '/sites/default/files/imagecache/' . $node->type . '-view/imagefield_default_images/gsapp-default.png';
		echo '<span class="'. $node->type .'"></span><img src="' . $image . '" alt="' . $title . '" width="' . $width . '" height="' . $height . '" />';
	}
	echo '</a>';
	?>
</div>