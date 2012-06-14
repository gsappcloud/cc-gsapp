<?php
drupal_add_css('sites/all/themes/gsapp/css/dk_theme.css');
drupal_add_js('sites/all/themes/gsapp/scripts/jquery.dropkick-1.0.0.js');
?>
<div class="title">
	<h1><?=$title?></h1>
</div>
	
<div class="clearfix">
	<div id="facebook-col" class="col">
		<h3>Facebook</h3>
		<?=$node->field_facebook[0]['view']?>
		<a href="http://www.facebook.com/gsapp1881" class="action-button" target="_blank">Connect on Facebook</a>
	</div> <!--/facebook-col-->

	<div id="twitter-col" class="col">
		<h3>Twitter</h3>
		<?=$node->field_twitter[0]['view']?>
		<a href="http://www.twitter.com/ccgsapp" class="action-button" target="_blank">Follow @CCGSAPP</a>
	</div> <!--/twitter-col-->
</div> <!--/clearfix-->

<div class='clearfix' style="margin-top:100px">
	<h3>CC: Newsletter &amp; Magazine</h3>
	
	<?=$node->field_cc_newsletter[0]['view']?>
	<?php echo drupal_get_form('campaignmonitor_subscribe_form','182ea382680a5c3ab861084043ce2b31'); ?>
</div>