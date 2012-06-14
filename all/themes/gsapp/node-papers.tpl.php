<?php if(!$teaser) { ?>
	<?php $GLOBALS['id'] = 0; ?>
	<div class="nid" id="<?=$node->nid?>">
		<div id="title-author" class="title">
			<h1><?php echo $title; ?></h1>
			<?php if(strip_tags($node->field_author[0]['value'])) { $author = $node->field_author[0]['value']; }?>
			<?php if($author) { ?><span id="by">By <?=$author?></span><?php } ?>
		</div>
		
		<div id="type-date">
			Papers &mdash; <?=format_date($node->created, 'custom', 'm.d.y')?>
		</div>

		<?php if($node->field_forward[0]['value']) { ?>
		<div class="forward clearfix">
			<?=apply_media($node, $node->field_forward[0]['value'])?>
		</div>
		<?php } ?>

		<div id="article">
			<?=apply_media($node, $body)?>
			<img src="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . path_to_theme(); ?>/images/end.png" class="end">
			<?=output_notes($node)?>
		</div>
	</div>
	<?php $GLOBALS['id'] = 0; ?>
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


	
