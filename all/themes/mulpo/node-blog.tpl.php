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
		
		
		
		<?php
			// echo '<pre>';
			// print_r($node);
			// echo '</pre>';
			// $images = $node->field_images;
			// 			if (is_array($images) && $node->field_images[0][filepath]) {
			// 				foreach ($images as $img) {
			// 					$image = $img['filepath'];
			// 					echo "<img src='/$image' width='100' /> ";
			// 					
			// 					echo cckmediacollection_collect($node->nid, $img);
			// 					
			// 					echo '<br />';
			// 				}
			// 			}
			// 			
			// 			$videos = $node->field_videos;
			// 			if (is_array($videos) && $node->field_videos[0][filepath]) {
			// 				foreach ($videos as $vid) {
			// 					$video = $vid['filepath'];
			// 					echo "<a href='$video'>$video</a> ";
			// 					
			// 					echo cckmediacollection_collect($node->nid, $vid);
			// 					echo '<br />';
			// 				}
			// 			}
		?>
  </div>

<?php if ($links): ?>
  <div class="node-links"><?php print $links ?></div>
<?php endif; ?>

</div>
