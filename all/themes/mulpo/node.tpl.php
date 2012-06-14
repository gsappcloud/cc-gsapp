<div id="node-<?php print $node->nid ?>" class="node node-<?php print $node->type ?>">

<?php if (!$page): ?>
  <h2 class="teaser-title"><a href="<?php print $node_url ?>" title="<?php print $title ?>">
    <?php print $title ?></a></h2>
<?php endif; ?>

	<?php if ($submitted || $terms): ?>
      	<div class="post-date"><span class="post-month"><?php print (format_date($node->created, 'custom', 'F')) ?> <?php print (format_date($node->created, 'custom', 'd')) ?>, 
		<?php print (format_date($node->created, 'custom', 'Y')) ?></span> -- Posted by: <a href="/user/<?php print($node->name) ?>"><?php print($node->name) ?></a> <?php if ($terms): ?>in <?php print $terms ?><?php endif;?></div>
	<?php endif; ?>

  <div class="content clear-block">
    <?php print $picture ?>
    <?php print $content ?>
  </div>

<?php if ($links): ?>
  <div class="node-links"><?php print $links ?></div>
<?php endif; ?>

</div>
