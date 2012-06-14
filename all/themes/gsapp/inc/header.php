<?php
	// links/active states
	if(strpos($_SESSION['argument0'], 'by') !== false) {
		$type = "all";
		$current = str_replace('by-', '', $_SESSION['argument0']);
	} elseif (arg(0) == 'search') {
		$type = "all";
		$current = str_replace('by-', '', arg(1));
	}	elseif ($_SESSION['argument0'] == "briefs" || $_SESSION['argument0'] == "notes" || $_SESSION['argument0'] == "papers") {
		$type = $_SESSION['argument0'];
		$typepath = $_SESSION['argument0'] . '/';
		$current = str_replace('by-', '', $_SESSION['argument1']);
	}
	
	// main menu current
	
	$alias = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$parts = explode('/', $alias);
	
	if($parts[1] == "magazine" || $parts[1] == "about") {
		$main_current = $parts[1];
	} elseif($parts[1] == "follow-cc") {
		$main_current = "follow";
	} elseif (arg(0) == "search") {
		$main_current = arg(0);
	} else {
		$main_current = "CC";
	}
	
	
	// menu state
	if($node->type == "briefs" || $node->type == "papers" || $node->type == "notes" || $parts[1] == "follow-cc"): $menu_state = "detail";
	elseif($parts[1] == "about"): $menu_state = "about";
	elseif($parts[1] == "magazine"): $menu_state = "magazine";
	endif;
	
	
	/* back button
	 * If the user got to this page via one of the views, a session will exist.  It is safe to use the http_referrer
	 * If not, just send them back to the homepage
	 */


	//default
	$back_text = "Back";
	$back = "javascript: nodeGoBack()";

	if (!isset($_SERVER['HTTP_REFERER'])) {
		$back = "http://".$_SERVER["HTTP_HOST"];
		$back_text = "Home";	
	}
	
?>
<div id="header" class="clearfix">
	<h1 id="logo"><a href="<?=$base_path?>">GSAPP</a></h1>
	<h2 id="info"><a href="<?=$base_path?>">A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION</a></h2>
	
<?php
	global $user;
	if ($template_files[0]!="page-widget") {
		if(user_access('administer') || in_array("editor", array_values($user->roles))) {	
?>
	<div class="nav admin">
		<div id="create-menu" class="menu<?php if($menu_state == "detail") echo ' blur'; ?>">
			<a href="#" class="current">Create</a>
			<ul>
				<li><a href="/node/add/notes">Note</a></li>
				<li><a href="/node/add/briefs">Brief</a></li>
				<li><a href="/node/add/papers">Paper</a></li>
				<li><a href="/node/add/magazine">Magazine</a></li>
				<li><a href="/node/add/cc-weekly-newsletter">CC Weekly</a></li>
				<li class="last"><a href="/node/add/event">Event</a></li>
				<!-- <li class="last"><a href="#">Newsletter</a></li> -->
			</ul>
		</div> <!--/create-->
		
		<div id="manage-menu" class="menu<?php if($menu_state == "detail") echo ' blur'; ?>">
			<a href="#" class="current">Admin</a>
			<ul>
				<li><a href="/admin/user/user">Users</a></li>
				<li><a href="/admin/content/node">Content</a></li>
				<li><a href="/admin/build/block/configure/block/1?destination=newsletter">Newsletter</a></li>
				<li class="last"><a href="/admin/reports">Reports</a></li>
			</ul>
		</div> <!--/manage-menu-->
		
		<div id="account-menu" class="menu<?php if($menu_state == "detail") echo ' blur'; ?>">
			<a href="#" class="current">My Account</a>
			<ul>
				<li><a href="/user/<?=$user->uid?>">View account</a></li>
				<li><a href="/user/<?=$user->uid?>/edit">Edit account</a></li>
				<li class="last"><a href="/logout">Logout</a></li>
			</ul>
		</div>
	</div> <!--/admin-->
		
		<?php } ?>
	
	<div class="nav">
		<div id="main-menu" class="menu<?php if($menu_state == "detail") echo ' blur'; ?>">
			<a href="#" class="current"><?=$main_current?></a>
			<ul>
				<li><a href="/" class="<?php echo ($main_current == "CC") ? 'active' : 'inactive'; ?>">CC</a></li>
				<li><a href="/about" class="<?php echo ($main_current == "about") ? 'active' : 'inactive'; ?>">About</a></li>
				<li><a href="/magazine" class="<?php echo ($main_current == "magazine") ? 'active' : 'inactive'; ?>">Magazine</a></li>
				<li><a href="/follow-cc" class="<?php echo ($main_current == "follow") ? 'active' : 'inactive'; ?>">Follow</a></li>
				<li><a href="http://www.gsapp.org" target="_blank">GSAPP</a></li>
				<li class="last" id="search-link"><a href="#" class="<?php echo ($main_current == "search") ? 'active' : 'inactive'; ?>">Search</a></li>
			</ul>
		</div>
		
		<?php
		
		include 'search-form.php';
		
		if($menu_state == "detail") {
			if($back) { ?>
			<div id="back-button" class="menu">
				<a href="<?=$back?>"><?=$back_text?></a>
			</div>
		<?php }
		} elseif ($menu_state == "magazine") {
			
		} elseif ($menu_state == "about") {
			
		} else { include 'views-menu.php'; }
		
		?>
	</div>
	
	<?php } ?>
	
</div> <!--/header-->