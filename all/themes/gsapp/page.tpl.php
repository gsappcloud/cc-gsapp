<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?=$head_title?></title>
	<?=$meta?>
  <?=$head?>
	<link href="http://cloud.webtype.com/css/a0868e2c-1109-4f64-8fbc-cd9f837ed961.css" rel="stylesheet" type="text/css" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?=$_SERVER['HTTP_HOST']?>/feed">
  <?=$styles?>	
</head>

<body class="<?=$body_classes?>">

<div class="wrapper">
	
  <?php 

	include('inc/header.php'); 
	
	if ($messages != '') {
    print '<div id="messages">'. $messages .'</div>';
  }

	if(user_access('administer') || in_array("editor", array_values($user->roles))) {
		echo '<div class="tab-wrapper">';
			
			/* unfortunately, there is a weird bug preventing tabs from displaying on the
				 programmatically created notes.  I don't have time to find a proper solution,
				 so I put together this little hack.
				
     		 I should've used drupal_execute instead of node_save to create them.
			 */
			if($node->type == "notes") {
				$viewstate = (arg(2) == "edit") ? 'inactive' : 'active';
				$editstate = (arg(2) == "edit") ? 'active' : 'inactive';
				echo '<ul class="tabs primary">';
				echo '<li class="' . $viewstate . '"><a class="' . $viewstate . '" href="/' . $node->path . '">View</a></li>';
				echo '<li class="' . $editstate . '"><a class="' . $editstate . '" href="/node/' . $node->nid . '/edit">Edit</a></li>';
				echo '</ul>';
			}	elseif ($tabs1) {
				 print '<ul class="tabs primary">'. $tabs1 .'</ul>';
			}
		  
			if ($tabs2){
			  $tabs2a = explode('&gt;li', $tabs2);
			    echo '<ul class="tabs secondary">';
				foreach($tabs2a as $slink) {
					echo str_replace('_', ' ', $slink);
				}
				echo '</ul>';                                               
		  }
		echo '</div>';
	}	
	
	// page titlez (not node titlez)
	if(($node && arg(2) == "edit") && $node->type != 'about' && $node->type != "follow") { ?>
		<div id="admin-title" class="title">
			<? if ($node->type == "magazine") { $type = "Magazine"; } else { $type = substr($node->type, 0, -1); } ?>
			<?="<h1>Editing a " . $type . "</h1>"?>
		</div>
	<?php	} elseif(($node && arg(2) == "edit") && $node->type == 'about') { ?>
		<div id="admin-title" class="title">
			<?="<h1>Editing " . $title . "</h1>"?>
		</div>
	<?php	} elseif(($node && arg(2) == "edit") && $node->type == 'follow') { ?>
		<div id="admin-title" class="title">
			<?="<h1>Editing " . $title . "</h1>"?>
		</div>
	<? } elseif (arg(0) == "admin" || arg(0) == "user") { ?>
		<div id="admin-title" class="title">
			<?="<h1>" . $title . "</h1>"?>
		</div>
	<? } elseif (arg(1) == "add") { ?>
		<div id="admin-title" class="title">
			<? if (arg(2) == "magazine") { $type = "Magazine"; } else { $type = substr(arg(2), 0, -1); } ?>
			<?="<h1>Creating a " . $type . "</h1>"?>
		</div>
	<? } elseif(arg(0) == "magazine" && !arg(1)) {
		echo '<div id="view-title" class="title"><h1>' . $title . '</h1></div>';
	}	elseif(arg(0) == "create-note") {
			echo '<div id="admin-title" class="title"><h1>' . $title . '</h1></div>';
	}	elseif(arg(2) == 'delete') {
		echo '<div id="admin-title" class="title"><h1>' . $title . '</h1></div>';
	}?>
	
	<div id="main-content" class="clearfix">
		<?=$content?>
	</div> <!--/main-content-->
</div> <!--/wrapper-->

<?php
// Show node footer except on these pages
if($node && arg(2) != "edit" && $node->type != "magazine" && $node->type != "about" && arg(2) != "delete" && $node->type != "follow") {
	 include('inc/node-footer.php');
}

echo $scripts;

// only include sharethis code on certain pages
$alias = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$parts = explode('/', $alias);
if(($node->type != "about" && $node->type != "follow" && $node) || $parts[1] == "magazine") { ?>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'b1bf80ce-3cf0-4214-a3c9-f2e8e543df05'});</script>
<?php } ?>
<?=$footer?>
<?=$closure?>
<?php //if($node) echo $GLOBALS['fullscreen_slideshow']; ?>

</body>
</html>