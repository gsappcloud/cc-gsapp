<?php
// filter results by node type
if(arg(0) == 'search') {
	$type = arg(3) ? arg(3) : "all";
?>
	<div id="type-menu" class="menu">
		<a href="#" class="current">View <?php echo $type; ?></a>
		<ul>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>search/by-<?=$current?>/<?=arg(2)?>" class="<?php echo ($type == "all") ? 'active' : 'inactive'; ?>">All</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>search/by-<?=$current?>/<?=arg(2)?>/papers" class="<?php echo ($type == "papers") ? 'active' : 'inactive'; ?>">Papers</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>search/by-<?=$current?>/<?=arg(2)?>/briefs" class="<?php echo ($type == "briefs") ? 'active' : 'inactive'; ?>">Briefs</a></li>
			<li class="last"><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>search/by-<?=$current?>/<?=arg(2)?>/notes" class="<?php echo ($type == "notes") ? 'active' : 'inactive'; ?>">Notes</a></li>
		</ul>
	</div>
<?php } else { ?>
	<div id="type-menu" class="menu">
		<a href="#" class="current">View <?php echo $type; ?></a>
		<ul>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>by-<?php echo $current; ?>" class="<?php echo ($type == "all") ? 'active' : 'inactive'; ?>">All</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>papers/by-<?php echo $current; ?>" class="<?php echo ($type == "papers") ? 'active' : 'inactive'; ?>">Papers</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>briefs/by-<?php echo $current; ?>" class="<?php echo ($type == "briefs") ? 'active' : 'inactive'; ?>">Briefs</a></li>
			<li class="last"><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' ?>notes/by-<?php echo $current; ?>" class="<?php echo ($type == "notes") ? 'active' : 'inactive'; ?>">Notes</a></li>
		</ul>
	</div>
<?php } ?>




<?php
// filter results by view
if(arg(0) == 'search') {
?>
	<div id="view-menu" class="menu">
		<a href="#" class="current">By <?php echo $current; ?></a>
		<ul>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/search/by-excerpt/<?=arg(2)?>" class="<?php echo ($current == "excerpt") ? 'active' : 'inactive'; ?>">Excerpt</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/search/by-image/<?=arg(2)?>" class="<?php echo ($current == "image") ? 'active' : 'inactive'; ?>">Image</a></li>
			<li class="last"><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/search/by-tags/<?=arg(2)?>" class="<?php echo ($current == "tags") ? 'active' : 'inactive'; ?>">Tags</a></li>
		</ul>
	</div>	
<?php } else { ?>
	<div id="view-menu" class="menu">
		<a href="#" class="current">By <?php echo $current; ?></a>
		<ul>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $typepath; ?>by-excerpt" class="<?php echo ($current == "excerpt") ? 'active' : 'inactive'; ?>">Excerpt</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $typepath; ?>by-image" class="<?php echo ($current == "image") ? 'active' : 'inactive'; ?>">Image</a></li>
			<li class="last"><a href="http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $typepath; ?>by-tags" class="<?php echo ($current == "tags") ? 'active' : 'inactive'; ?>">Tags</a></li>
		</ul>
	</div>
<?php } ?>