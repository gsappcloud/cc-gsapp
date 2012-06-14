<?php
// Get the footer links
$fields = null;
$fields .= $node->field_forward[0]['value'];
$fields .= $node->body;
?>

<div id="article-footer">
	<div class="wrapper footer">

		<div class="share clearfix section">
			<strong class="section-title">Share</strong>
			<div class="content">
				<?php include 'share.php'; ?>
			</div>
		</div> <!--/share-->

		<?php if($node->taxonomy) { ?>
		<div class="tags clearfix section">
			<strong class="section-title">Tags</strong>
			<div class="content">
				<?php
					$tags = array();
					foreach ($node->taxonomy as $term) {
						if($term->vid == 1) {
							$tags[] = '<a href="' . $base_path . 'search/by-excerpt/' . $term->name . '" title="' . $term->name . '" class="category">' . $term->name . '</a>';
						}
					}
					echo implode(', ', $tags);
				?>
			</div>
		</div>
		<?php } ?>

		<?php if($node->field_locations[0]['view']) { ?>
		<div class="region clearfix section">
			<strong class="section-title">Region</strong>
			<div class="content">
				<?php
					// display the google map
					echo $node->field_locations[0]['view'];
				?>
			</div>
		</div>
		<?php } ?>

		<?php if ($footnotes = get_footnotes($fields)) { ?>
		<div class="links clearfix section">
			<strong class="section-title">Links from this article</strong>
			<div class="content">
				<?php					
					for($x = 0; $x < count($footnotes); $x++) {
				
						if($x%10 == 0) echo '<ul>';
				
						$count = $x+1;
						$count = sprintf("%02d",$count);
				
						echo '<li><strong>' . $count . '</strong>' . format_footnote_link($footnotes[$x]) . '</li>';
				
						if(($x+1)%10 == 0) echo '</ul>'; 
					}	
				?>
			</div>
		</div>
		<?php } // end footnotes ?>
		
		
		<div class="comment-form-wrapper clearfix section">
			<?php
				echo '<strong class="section-title">Comments</strong>';
				echo '<div class="content">';
				echo $commentz; // disqus block asigned to this var in admin/build/blocks
				echo '</div>';
			?>
		</div>
	</div> <!--/wrapper-->
</div> <!--/article-footer-->