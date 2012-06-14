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
	<div class="type"><?php echo $node->type; ?>
		<div class="rollover">
		<?php
			$image = animated($node, 'excerpt', false, null);
			echo '<img src="' . $image . '" alt="' . $title . '" width="220" height="165" />';
		?>
		</div> <!--/rollover-->
	</div>
	<a target="_blank" href="/<?php echo $node->path; ?>"><?php echo strip_tags($node->field_excerpt[0]['value']); ?></a>
</div>