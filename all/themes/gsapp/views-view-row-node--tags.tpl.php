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
	<span class="type"><?=$node->type?>
		<div class="rollover">
		<?php
			$image = animated($node, 'tags', false, null);
			echo '<img src="' . $image . '" alt="' . $title . '" width="220" height="165" />';
		?>
		</div> <!--/rollover-->
	</span>
	<?php
		// tags
		$tags = array();
		foreach ($node->taxonomy as $term) {
			if($term->vid == 1) {
				$tags[] = $term->name;
			}
		}
		echo '<a target="_blank" href="/' . $base_path . $node->path . '">'. implode(', ', $tags) . '</a>';
	
	?>
</div>