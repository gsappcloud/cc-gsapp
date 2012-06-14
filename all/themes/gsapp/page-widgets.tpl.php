<!DOCTYPE HTML>
<html lang="en-US" style="overflow:hidden">
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

	<div id="header" class="clearfix">
		<h2 id="info"><a target="_blank" href="<?=$base_path?>">A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION</a></h2>
		<h1 id="logo"><a href="javascript:"><img src="http://ccgsapp.org/sites/all/themes/gsapp/images/logo.png" width="170"></a></h1>
	</div>
	
	<div id="main-content" class="clearfix">
		<?=$content?>
	</div> <!--/main-content-->
	
	<div id="widget-controls">
		<a class="item" target="_blank" href='http://ccgsapp.org'>READ MORE AT CCGSAPP.ORG</a> 
		<img id="small-logo" src="http://ccgsapp.org/sites/all/themes/gsapp/images/logo.png">
	</div>	
</div> <!--/wrapper-->

<?php echo $scripts; ?>
<?=$footer?>
<?=$closure?>
<script type="text/javascript" charset="utf-8">
	$(window).hashchange(function(){
		var hash = location.hash.replace("#","");
		if (hash=="collapse") {
			$("body").addClass("widget-collapsed");
		}else{
			$("body").removeClass("widget-collapsed");
		}
	})
</script>
</body>
</html>