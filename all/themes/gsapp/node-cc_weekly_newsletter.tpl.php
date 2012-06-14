<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">


<?php
print <<<EOT
<script type="text/javascript" src="/misc/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//if newsletter view hide all page elements for copy-paste
	var loc = new String(window.location);
	if (loc.indexOf('/ccweeklynewsletter/', 0) > 0) {
		$('#header').remove();
		$('#article-footer').remove();
		
		$('#newsletter-output').css({
			'width': '80%',
			'font-family': 'monospace, courier',
			'font-size': '18px',
			'background-color': '#ddd',
			'padding': '10px',
			'margin': '10px auto 10px auto'});
		$('p').css('margin', '0');
		
	}
});
</script>
EOT;

$title = $node->title;
$intro = htmlentities($node->field_weekly_intro[0]['safe']);
$intro_non = $node->field_weekly_intro[0]['safe'];
$intro2 = str_replace("\n", '&lt;br&#47;&gt;', $intro);
$intro2 = str_replace('&lt;/p&gt;&lt;br&#47;&gt;', '&lt;/p&gt;', $intro2);



$events = $node->field_weekly_events;
$highlights = $node->field_weekly_past_highlights;

$event_data = array();
foreach($events as $event) {
	$evt = array();
	$en = node_load($event['nid'], NULL, True);
	//print_r($en);
	
	$evt['city'] = $en->field_event_city[0]['value'];
	$evt['title'] = $en->title;

	$date = $en->field_event_date[0]['value'];
	$date_to = $en->field_event_date[0]['value2'];
	
	
	// what about 2 dates?
	// annoying date conversion
	$ts = array();
		$ts['year'] = substr($date, 0, 4);
		$ts['month'] = substr($date, 5, 2);
		$ts['day'] = substr($date, 8, 2);
		$ts['hour'] = substr($date, 11, 2);
		$ts['minute'] = substr($date, 14, 2);
	$ts_r = mktime($ts['hour'], $ts['minute'], 0, $ts['month'], $ts['day'], $ts['year']);

	$ts2 = array();
		$ts2['year'] = substr($date_to, 0, 4);
		$ts2['month'] = substr($date_to, 5, 2);
		$ts2['day'] = substr($date_to, 8, 2);
		$ts2['hour'] = substr($date_to, 11, 2);
		$ts2['minute'] = substr($date_to, 14, 2);
	$ts2_r = mktime($ts2['hour'], $ts2['minute'], 0, $ts2['month'], $ts2['day'], $ts2['year']);


	//TODO what if FROM and TO dates are different?
	
	$evt['from-date'] = date('l, F j, Y', $ts_r);
	$event_time = date('g:iA', $ts_r) . ' &mdash; ' . 
	date('g:iA', $ts2_r);
	$evt['from-time'] = $event_time;
	
	$evt['to-date'] = date('l, F j, Y', $ts_r);
	$evt['to-time'] = null;

	$evt['location'] = $en->field_event_location[0]['value'];
	$evt_d = $en->field_event_description[0]['value'];
	// must strip additional paragraph tags
	$evt_d = trim($evt_d);
	$evt_d = substr($evt_d, 4, -4);
	$evt_d = trim($evt_d);
	$evt['description'] = $evt_d;
	$evt['moreinfo'] = $en->field_event_moreinfo[0]['url'];
	$evt['gcal'] = $en->field_event_gcal[0]['url'];

	//TODO  do we want images?
	// field_event_image
	if (strlen($evt['city']) > 4) {
		$event_data[] = $evt;
	}
}

$highlight_data = array();
foreach($highlights as $highlight) {
	
	$h = array();
	
	$hn = node_load($highlight['nid'], NULL, True);
	
	$h['excerpt'] = $hn->field_excerpt[0]['value'];
	$h['path'] = $hn->path;
	$i = $hn->field_images;
	if (count($i) > 0) {
		$imgtag = theme('imagecache', 'past-week-highlight_newsletter_only', $i[0]['filepath'], '', '');
		$closing_tag_pos = strpos($imgtag, '/>');
		$new_tag = substr($imgtag, 0, $closing_tag_pos)
			. 'style="border: none;" />';
		$h['image'] = $new_tag;
	}
	$highlight_data[] = $h;
}

// TODO must strip out BR tags inserted by RTF editor...


$br = '&lt;br&#47;&gt;';


$output_html = '<div id="newsletter-output">' . 
		htmlentities('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">') .
		htmlentities('<head><title>CC:GSAPP TITLE</title>') .
		htmlentities('</head><body><table cellpadding="10" cellspacing="10" width="100%"><tr>') .
		htmlentities('<td align="center">') . $br .
		htmlentities('<table class="main-content" cellpadding="0" cellspacing="0" width="705" style="margin-left: auto; margin-right: auto;">') .
		htmlentities('<tr valign="top"><td>') . $br . 
		htmlentities('<table cellpadding="0" cellspacing="0" width="705" style="font-family: Georgia;font-size: 18px;">
<tr valign="top"><td><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_header.png" width="705" height="228" alt="CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" /><br/><br/></td></tr>
<tr><td align="center" style="height: 20px;">&nbsp;</td>
</tr><tr valign="top"><td align="left" style="font-family: Georgia;">') . $intro2 .
		htmlentities('</td></tr><tr><td align="center" style="height: 20px;">&nbsp;</td></tr></table>') .
		htmlentities('<table cellpadding="5" cellspacing="10" width="705" style="font-family: Georgia;background:#0089ff; color:white; padding:20px 0; margin-top:20px;">') .
		htmlentities('<tr valign="top"><td colspan="2" align="center"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_eventsThisWeek.png" alt="EVENTS THIS WEEK FROM THE CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" style="display:inline-block;margin-left:15px;margin-bottom:5px;"/>' . '</td></tr>') .
		htmlentities('<tr><td colspan="2" align="center">&nbsp;</td></tr>');

$output_non_html = '<div id="newsletter-output-non">' . 
		'<table cellpadding="10" cellspacing="10" width="100%"><tr>' .
		'<td align="center"><br/>' .
		'<table class="main-content" cellpadding="0" cellspacing="0" width="705" style="margin-left: auto; margin-right: auto;">' .
		'<tr valign="top"><td><br/>' .
		'<table cellpadding="0" cellspacing="0" width="705" style="font-family: Georgia;font-size: 18px;"><tr valign="top"><td>' . 
		'<img src="http://www.gsapp-is.org/CC/cloud/110918/110917_header.png" width="705" height="228" alt="CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" /><br/><br/></td></tr>' . 
		'<tr><td align="center" style="height: 20px;">&nbsp;</td>' . 
		'</tr><tr valign="top"><td align="left" style="font-family: Georgia;">' . 
		$intro_non .
		'</td></tr><tr><td align="center" style="height:20px;">&nbsp;</td></tr></table>' . 
		'<table cellpadding="5" cellspacing="10" width="705" style="font-family: Georgia;background:#0089ff; color:white; padding:20px 0; margin-top:20px;">'.
		'<tr valign="top"><td colspan="2" align="center"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_eventsThisWeek.png" alt="EVENTS THIS WEEK FROM THE CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" style="display:inline-block;margin-left:15px;margin-bottom:5px;"/>' . '</td></tr>' .
		'<tr><td colspan="2" align="center">&nbsp;</td></tr>';
		

		// events
		foreach($event_data as $evt) {
			$output_html .= htmlentities('<tr><td valign="top" width="120" align="center"><a href="' . $evt['moreinfo'] . '" target="_blank"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_moreInfo.png" width="101" height="32" alt="MORE INFO" title="Find out more" style="border: none;" /></a><br/><br/>') .
			htmlentities('<a href="' . $evt['gcal'] . '" target="_blank">' . 
			'<img src="http://www.gsapp-is.org/CC/cloud/110918/110917_addToCal.png" width="101" height="32" alt="ADD THIS EVENT TO YOUR CALENDAR" title="Add to Calendar" style="border: none;"/></a><br/></td>') . 
			htmlentities('<td valign="top" align="left" width="350" style="color: #ffffff; font-size: 16px; padding-right:20px;"><p style="font-size: 9px; color: #000000; font-weight: bold; padding-top: 0; margin-top: 0;">') . strtoupper($evt['city']) . 
			htmlentities('</p><p style="color: #ffffff;">') . 
			$evt['title'] . htmlentities('<br/><br/>') .
			$evt['from-date'] . $br . $evt['from-time'] . $br .
			$evt['location'] .
			htmlentities('</p><p style="color: #000000; font-size: 14px;">') . 
			htmlentities($evt['description']) .
			htmlentities('</p></td></tr>');
			
			$output_non_html .= '<tr><td valign="top" width="120" align="center"><a href="' . $evt['moreinfo'] . '" target="_blank"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_moreInfo.png" width="101" height="32" alt="MORE INFO" title="Find out more" style="border: none;" /></a><br/><br/>' .
			'<a href="' . $evt['gcal'] . '" target="_blank">' . 
			'<img src="http://www.gsapp-is.org/CC/cloud/110918/110917_addToCal.png" width="101" height="32" alt="ADD THIS EVENT TO YOUR CALENDAR" title="Add to Calendar" style="border: none;"/></a><br/></td>' . 
			'<td valign="top" align="left" width="350" style="color: #ffffff; font-size: 16px; padding-right:20px;"><p style="font-size: 9px; color: #000000; font-weight: bold; padding-top: 0; margin-top: 0;">' . strtoupper($evt['city']) . 
			'</p><p style="color: #ffffff;">' . 
			$evt['title'] . '<br/><br/>' .
			$evt['from-date'] . '<br/>' . $evt['from-time'] . '<br/>' .
			$evt['location'] .
			'</p><p style="color: #000000; font-size: 14px;">' . 
			$evt['description'] .'</p></td></tr>';
			
		}
		$output_non_html .= '</table><!-- end events -->';
		$output_html .= htmlentities('</table><!-- end events -->');

		// past highlights
		$output_html .= htmlentities('<table cellpadding="0" cellspacing="10" width="705" style="margin-top: 20px; font-size: 18px;"><tr valign="top" align="center"><td colspan="3"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_highlights_bg.png"  alt="HIGHLIGHTS FROM LAST WEEK &ndash; CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" style="display:inline-block;margin-left:15px;margin-bottom:25px;" width="414" height="92" /><br/></td></tr><tr valign="top">');
		
		$output_non_html .='<table cellpadding="0" cellspacing="10" width="705" style="margin-top: 20px; font-size: 18px;"><tr valign="top" align="center"><td colspan="3"><img src="http://www.gsapp-is.org/CC/cloud/110918/110917_highlights_bg.png"  alt="HIGHLIGHTS FROM LAST WEEK &ndash; CC: NEWSLETTTER &ndash; A GLOBAL REPORT FROM THE COLUMBIA UNIVERSITY GRADUATE SCHOOL OF ARCHITECTURE, PLANNING AND PRESERVATION" style="display:inline-block;margin-left:15px;margin-bottom:25px;" width="414" height="92" /><br/></td></tr><tr valign="top">';
		
		foreach($highlight_data as $hlt) {
		
			

			$output_html .= htmlentities('<td width="230" align="center">') .
											htmlentities('<a style="text-decoration: none; color: #000000;" target="_blank" href="http://ccgsapp.org/') . $hlt['path'] .
											htmlentities('">') .
											htmlentities($hlt['image']) . 
											htmlentities('</a>') . $br . $br .
											htmlentities('<a style="text-decoration: none; color: #000000;" href="http://ccgsapp.org/' . $hlt['path'] . '">') .
											$hlt['excerpt'] .
											htmlentities('</a></td>');
			$output_non_html .= '<td width="230" align="center">' .
											'<a style="text-decoration: none; color: #000000;" target="_blank" href="http://ccgsapp.org/' . $hlt['path'] . '">' .
											$hlt['image'] . '</a><br/><br/>' .
											'<a style="text-decoration: none; color: #000000;" href="http://ccgsapp.org/' . $hlt['path'] . '">' . $hlt['excerpt'] . '</a></td>';
		}
		
		$output_html .= htmlentities('</tr><tr valign="top"><td colspan="2">') . 
		htmlentities('<a href="http://ccgsapp.org"><img src="http://ccgsapp.org/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_readmore.png" alt="Read more at ccgsapp.org" style="border: none;" /></a></td>') . 
		htmlentities('<td style="text-align: right;"><a href="http://www.facebook.com/gsapp1881" target="_blank"><img src="http://ccgsapp.org/sites/all/themes/gsapp/images/newsletter/like.png" alt="Like" style="border: none;" /></a></td></tr>') .
		htmlentities('<tr valign="top"><td colspan="3"><p style="text-align:left;font-size:11px; line-height:14px;">Columbia University&nbsp;&nbsp;1172 Amsterdam Ave.&nbsp;&nbsp;New York, NY 10027</p>').
		htmlentities('<p style="text-align:left;font-size:11px; line-height:14px;"><a href="http://ccgsapp.org/about" style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;">') .
		htmlentities('Privacy Policy</a>&nbsp;&nbsp;<a style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;" href="http://ccgsapp.org/about">Terms of Use</a>&nbsp;&nbsp;&nbsp;&nbsp;') .
		htmlentities('<!-- mailchimp specific --><unsubscribe style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;">Unsubscribe</unsubscribe></p></td></tr></table></td></tr></table></body></html>');
		$output_html .= '</div>';
		
		$output_non_html .= '</tr><tr valign="top"><td colspan="2">' . 
			'<a href="http://ccgsapp.org"><img src="http://ccgsapp.org/sites/all/themes/gsapp/images/newsletter/ccgsapp_newsletter_readmore.png" alt="Read more at ccgsapp.org" style="border: none;" /></a></td>' . 
			'<td style="text-align: right;"><a href="http://www.facebook.com/gsapp1881" target="_blank"><img src="http://ccgsapp.org/sites/all/themes/gsapp/images/newsletter/like.png" alt="Like" style="border: none;" /></a></td></tr>' .
			'<tr valign="top"><td colspan="3"><p style="text-align:left;font-size:11px; line-height:14px;">Columbia University&nbsp;&nbsp;1172 Amsterdam Ave.&nbsp;&nbsp;New York, NY 10027</p>'.
			'<p style="text-align:left;font-size:11px; line-height:14px;"><a href="http://ccgsapp.org/about" style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;">Privacy Policy</a>&nbsp;&nbsp;<a style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;" href="http://ccgsapp.org/about">Terms of Use</a>&nbsp;&nbsp;&nbsp;&nbsp;<!-- mailchimp specific --><unsubscribe style="color: #0089FF; font-family: Georgia,serif !important; text-decoration: none;">Unsubscribe</unsubscribe></p></td></tr></table></td></tr></table></body></html>';

		$output_non_html .= '</div>';

		print $output_non_html . '<br/><hr><br/>';
		print $output_html;



?>



</div>