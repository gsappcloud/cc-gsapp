<?php
 	if(!$teaser) { ?>
	<div class="nid" id="<?=$node->nid?>">
		<div id="title-author" class="title">
			<h1><?=$title?></h1>
			<?php if(strip_tags($node->field_author[0]['value'])) { $author = $node->field_author[0]['value']; }?>
			<?php if($author) { ?><span id="by">By <?=$author?></span><?php } ?>
		</div>
		
		<div id="type-date">
			<?php if($node->field_source[0]['url']) { 
				echo 'via ' . $node->field_source[0]['view'] . '<br />';
			} ?>
			
			<?php if(strtolower($title) != 'notes') { echo 'Notes &mdash;'; } ?> <?php echo format_date($node->created, 'custom', 'm.d.y'); ?>
		</div>

		<?php if($node->field_forward[0]['value']) { ?>
		<div class="forward clearfix">
			<?=$node, $node->field_forward[0]['value']?>
		</div>
		<?php } ?>

		<div id="article">
			<?=apply_media($node, $body)?>
		</div>
	</div>
<?php } ?>
<?php

    if($page!=0)
    {
        $previous_node_link = previous_node($node, NULL, NULL, NULL);
        $next_node_link = next_node($node, NULL, NULL, NULL);    
        
        print '<div class="previous-next-links">';
        if($previous_node_link && $next_node_link)
        {
            print $previous_node_link.' '.$next_node_link;
        }
        else if($previous_node_link)
        {
            print $previous_node_link;
        }
        else if($next_node_link)
        {
            print $next_node_link;
        }
        print '</div>';
    }
?>
	
