<?php

/**
 * Template for the Newsletter View.
 * 
 * Note: $view->result is filled with top 10 nodes provided by the Radioactivity module
 * 
 */

$blocks = block_list("footer");
$nextmonthBlock = $blocks["block_1"]->content;

$domain = $_SERVER["HTTP_HOST"];
$nodes = array();

foreach ($view->result as $node) {
	$nodes[] = $node->nid;
}

ob_start();	
?>
<html>
<style type="text/css">
	body {font: 18px Georgia, serif !important; line-height:24px; margin:20px 0; padding:0; text-align:center}
	
	a {color:#0089ff; text-decoration:none; font-family:Georgia, serif !important;}
	a:hover {color:#0089FF !important;}
	tr { vertical-align:top}
	td {padding:0 5px}
	webversion, unsubscribe {color:#0089ff; font-size:11px; text-align:left;}

	.nodes-row td {padding-bottom:15px;}

	.nodeimg {display:inline-block; margin-bottom:10px; margin:0 10px 10px;}
	.indeximg {display:inline-block; margin:15px 0}

	.wrapper {max-width:700px; margin:0 auto; padding:0 30px}
	.blue {background:#0089ff; color:white; padding:20px 0; margin-top:20px}	
	.footer {font-size:11px; line-height:14px; text-align:left;}
	.big {font-size:28px; line-height:34px; font-weight:normal; margin-bottom:30px}
	.nodelink {color:black; text-decoration:none;}
	.blue p, .left {text-align:left; line-height:24px;}
	.blue a {color:black;}
	.blue a:hover {color:#222 !important;}
	
	li {text-align:left;}
	.footer-link {
		margin:10px 0;
		display:block;
		float:left;
		width:50%;
	}
	.right {
		text-align:right;
	}
</style>
<body>
	<div class="header">
		<p class='wrapper footer' style="padding-left:45px; color:#AAA">Trouble viewing this email? <webversion>View in Browser</webversion><br><br></p>
		<img src="http://<?=$domain?>/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_header.png" alt="CC: NEWSLETTTER – A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" style="display:inline-block;margin-left:15px">
	</div>
	<div class="wrapper">
		<?php
		$first_node = node_load(array_shift($nodes));
		
		echo "<div class='big'><a href='http://".$domain."/".$first_node->path."' class='nodelink'>";
		if ($first_node->field_images[0]!=null) {
			$image = imagecache_create_url("fullscreen-image", $first_node->field_images[0]["filepath"]);
			echo "<img class='nodeimg' src='$image' width='700' /><br>";
		}
		echo $first_node->field_excerpt[0]["value"];
		echo "</a></div>" ?>
		<table border="0" cellspacing="0" cellpadding="0" style="text-align:center; font-size:18px; line-height:24px;">
			<tr>
				<td colspan="3"><img class="indeximg" src="http://<?=$domain?>/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_2_4.png" /></td>
			</tr>
			<tr class='nodes-row'>
			<?php
			$count = 0;
			foreach ($nodes as $nid) {
				if ($count==9) break;
				
				$node = node_load($nid);
				if ($node->field_images[0]==null) continue;
				
				if ($count!=0 && $count%3==0) {
					echo "</tr><tr><td colspan='3'>";
					echo sprintf('<img class="indeximg" src="http://%s/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_%d_%d.png" />',
						$domain, $count+2, $count+4);
					echo "</td></tr>";
					echo "<tr class='nodes-row'>";
				}
				
				$imgsrc = imagecache_create_url("briefs-view", $node->field_images[0]["filepath"]);
				$img = "<img class='nodeimg' src='$imgsrc' width='221' /><br>";
		
				echo "<td>";
				echo sprintf("<a href='%s' class='nodelink'>%s %s</a>","http://".$domain."/".$node->path,$img,$node->field_excerpt[0]["value"]);
				echo "</td>";
				$count++;
			}
			
			?>
			</tr>
		</table>
	</div>
	<div class='blue'>
		<div class="wrapper">
			<p style="text-align:center"><img src="http://<?=$domain?>/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_nextmonth.png" /></p>
			<?=$nextmonthBlock?>
		</div>
	</div>
	<div class='footer'>
		<div class='wrapper'>
			<p class='left'>				
				<a class='footer-link' href="http://ccgsapp.org">
					<img src="http://<?=$domain?>/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_readmore.png" />
				</a><a class='footer-link right' href="http://ccgsapp.org/follow-cc">
					<img src="http://<?=$domain?>/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_subscribe.png" />
				</a>
				<br>
				Columbia University&nbsp;&nbsp;1172 Amsterdam Ave.&nbsp;&nbsp;New York, NY 10027
				<br><br><br><br><br>
				<a href="http://<?=$domain?>/about">Privacy Policy</a>&nbsp;&nbsp;<a href="http://<?=$domain?>/about">Terms of Use</a>&nbsp;&nbsp; <unsubscribe>Unsubscribe</unsubscribe>
			</p>
			
		</div>
	</div>
</body>
</html>

<?php 

$out = ob_get_contents();
ob_end_clean();

// foreach ($styles["tag"] as $tag => $style) {
// 	//output inline styles for tags
// 	// $out = str_replace("<$tag>","<$tag style=\"$style\">",$out);
// 	$out = preg_replace("/\<".$tag."([^\>]*)\>/mis","<$tag $1 style=\"$style\">",$out);
// }
// foreach ($styles["class"] as $class => $style) {
// 	//output inline styles for classes
// 	$out = preg_replace("/class=[\'\"]".$class."[\'\"]/mis","class=\"$class\" style=\"$style\"",$out);
// }

die($out); 


?>