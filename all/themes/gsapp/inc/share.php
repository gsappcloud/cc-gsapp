<div class="share-box">
	<div class="facebook">
		<iframe src="http://www.facebook.com/plugins/like.php?href=http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $node->path; ?>&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:24px;" allowTransparency="true"></iframe>
	</div> <!--/facebook-->

	<div class="other">
		<a href="http://twitter.com/share?url=http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $node->path; ?>&amp;text=Check%20this%20out!" class="twitter-share st_button">Twitter</a><a href="http://www.facebook.com/sharer.php?u=http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $node->path; ?>&amp;t=GSAPP CC" class="facebook-share st_button">Facebook</a><span class="st_ybuzz_custom st_button">Yahoo! Buzz</span><span class="st_gbuzz_custom st_button">Google Buzz</span><span class="st_digg_custom st_button">Digg</span><span class="st_delicious_custom st_button">Delicious</span><span class="st_reddit_custom st_button">Reddit</span><span class="st_tumblr_custom st_button" st_url="http://www.tumblr.com" st_title="Tumblr"
		displayText="Tumblr"></span><span class="st_email_custom st_button">Email</span><span class="st_sharethis_custom st_button">ShareThis</span>
	</div>
	
	<?php if(arg(0) == 'magazine') { ?>
		<a class="close">Close</a>
	<?php } ?>
</div> <!--/share-box-->