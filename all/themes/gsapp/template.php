<?php
/**
 * Uncomment the following line during development to automatically
 * flush the theme cache when you load the page. That way it will
 * always look for new tpl files.
 */
 // drupal_flush_all_caches();
/**
 * Intercept page template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function gsapp_preprocess_page(&$vars) {
	jquery_ui_add(array('ui.draggable'));
		
  global $user;
  $vars['path'] = base_path() . path_to_theme() .'/';
  $vars['user'] = $user;
	
	global $base_path;
	global $base_url;
	
	$GLOBALS['id'];
	
	// print_r($vars);
	
	// body classes
	if(user_access('administer') || in_array("editor", array_values($user->roles))) $vars['body_classes'] .= " admin";
	if(arg(2) == "delete") $vars['body_classes'] .= " delete";

	// set up tabs for editors
	$vars['tabs1'] = menu_primary_local_tasks();
	$vars['tabs2'] = menu_secondary_local_tasks();

  //Play nicely with the page_title module if it is there.
  if (!module_exists('page_title')) {
    // Fixup the $head_title and $title vars to display better.
    $title = drupal_get_title();
    $headers = drupal_set_header();
    
    // wrap taxonomy listing pages in quotes and prefix with topic
    if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
      $title = t('Topic') .' &#8220;'. $title .'&#8221;';
    }
    // if this is a 403 and they aren't logged in, tell them they need to log in
    else if (strpos($headers, 'HTTP/1.1 403 Forbidden') && !$user->uid) {
      $title = t('Please login to continue');
    }
    $vars['title'] = $title;

    if (!drupal_is_front_page()) {
      $vars['head_title'] = $title .' | '. $vars['site_name'];
      if ($vars['site_slogan'] != '') {
        $vars['head_title'] .= ' &ndash; '. $vars['site_slogan'];
      }
    }
  }

	// Page change based on node->type
  // Add a new page-TYPE template to the list of templates used
  if (isset($vars['node'])) {
    // Add template naming suggestion. It should alway use hyphens.
    $vars['template_files'][] = 'page-'. str_replace('_', '-', $vars['node']->type);   
  }

	// $vars['comments'] = $vars['comment_form'] = '';
  if (module_exists('comment') && isset($vars['node'])) {
    $vars['comments'] = comment_render($vars['node']);
    $vars['comment_form'] = drupal_get_form('comment_form', array('nid' => $vars['node']->nid));
  }

  $vars['meta'] = '';
  // SEO optimization, add in the node's teaser, or if on the homepage, the mission statement
  // as a description of the page that appears in search engines
  if ($vars['is_front'] && $vars['mission'] != '') {
    $vars['meta'] .= '<meta name="description" content="'. gsapp_trim_text($vars['mission']) .'" />'."\n";
  }
  else if (isset($vars['node']->teaser) && $vars['node']->teaser != '') {
    $vars['meta'] .= '<meta name="description" content="'. gsapp_trim_text($vars['node']->teaser) .'" />'."\n";
  }
  else if (isset($vars['node']->body) && $vars['node']->body != '') {
    $vars['meta'] .= '<meta name="description" content="'. gsapp_trim_text($vars['node']->body) .'" />'."\n";
  }
  // SEO optimization, if the node has tags, use these as keywords for the page
  if (isset($vars['node']->taxonomy)) {
    $keywords = array();
    foreach ($vars['node']->taxonomy as $term) {
      $keywords[] = $term->name;
    }
    $vars['meta'] .= '<meta name="keywords" content="'. implode(',', $keywords) .'" />'."\n";
  }

  // SEO optimization, avoid duplicate titles in search indexes for pager pages
  if (isset($_GET['page']) || isset($_GET['sort'])) {
    $vars['meta'] .= '<meta name="robots" content="noindex,follow" />'. "\n";
  }

  /* I like to embed the Google search in various places, uncomment to make use of this
  // setup search for custom placement
  $search = module_invoke('google_cse', 'block', 'view', '0');
  $vars['search'] = $search['content'];
  */
  
  /* to remove specific CSS files from modules use this trick
  // Remove stylesheets
  $css = $vars['css'];
  unset($css['all']['module']['sites/all/modules/contrib/plus1/plus1.css']);
  $vars['styles'] = drupal_get_css($css);   
  */

	drupal_add_js('sites/all/themes/gsapp/scripts/jquery.animate-colors-min.js');
}

/**
 * Intercept node template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function gsapp_preprocess_node(&$vars) {
	global $base_path;
	global $base_url;
	
  $node = $vars['node']; // for easy reference
  // for easy variable adding for different node types
  switch ($node->type) {
    case 'page':
      break;
  }

	$vars['node']->comment = 0;
}

/**
 * Intercept comment template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function gsapp_preprocess_comment(&$vars) {
  static $comment_count = 1; // keep track the # of comments rendered
  
  // Calculate the comment number for each comment with accounting for pages.
  $page = 0;
  $comments_previous = 0;
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $comments_per_page = variable_get('comment_default_per_page_' . $vars['node']->type, 1);
    $comments_previous = $comments_per_page * $page;
  }
  $vars['comment_count'] =  $comments_previous + $comment_count;
    
  // if the author of the node comments as well, highlight that comment
  $node = node_load($vars['comment']->nid);
  if ($vars['comment']->uid == $node->uid) {
    $vars['author_comment'] = TRUE;
  }

  // If comment subjects are disabled, don't display them.
  if (variable_get('comment_subject_field_' . $vars['node']->type, 1) == 0) {
    $vars['title'] = '';
  }

  // Add the pager variable to the title link if it needs it.
  $fragment = 'comment-' . $vars['comment']->cid;
  $query = '';
  if (!empty($page)) {
    $query = 'page='. $page;
  }
  $vars['title'] = l($vars['comment']->subject, 'node/'. $vars['node']->nid, array('query' => $query, 'fragment' => $fragment));
  $vars['comment_count_link'] = l('#'. $vars['comment_count'], 'node/'. $vars['node']->nid, array('query' => $query, 'fragment' => $fragment));


  $comment_count++;
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function gsapp_preprocess_block(&$vars, $hook) {
  $block = $vars['block'];

  // Special classes for blocks.
  $classes = array('block');
  $classes[] = 'block-' . $block->module;
  $classes[] = 'region-' . $vars['block_zebra'];
  $classes[] = $vars['zebra'];
  $classes[] = 'region-count-' . $vars['block_id'];
  $classes[] = 'count-' . $vars['id'];

  $vars['edit_links_array'] = array();
  $vars['edit_links'] = '';
  
  if (user_access('administer blocks')) {
    include_once './' . drupal_get_path('theme', 'gsapp') . '/template.block-editing.inc';
    gsapp_preprocess_block_editing($vars, $hook);
    $classes[] = 'with-block-editing';
  }

  // Render block classes.
  $vars['classes'] = implode(' ', $classes);
}


/**
 * Intercept box template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function gsapp_preprocess_box(&$vars) {
  // rename to more common text
  if (strpos($vars['title'], 'Post new comment') === 0) {
    $vars['title'] = 'Add your comment';
  }
}

/**
 * Override, remove "not verified", confusing
 *
 * Format a username.
 *
 * @param $object
 *   The user object to format, usually returned from user_load().
 * @return
 *   A string containing an HTML link to the user's page if the passed object
 *   suggests that this is a site user. Otherwise, only the username is returned.
 */
function gsapp_username($object) {
  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }

    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('attributes' => array('title' => t('View user profile.'))));
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }

  return $output;
}

/**
 * Override, make sure Drupal doesn't return empty <P>
 *
 * Return a themed help message.
 *
 * @return a string containing the helptext for the current page.
 */
function gsapp_help() {
  $help = menu_get_active_help();
  // Drupal sometimes returns empty <p></p> so strip tags to check if empty
  if (strlen(strip_tags($help)) > 1) {
    return '<div class="help">'. $help .'</div>';
  }
}

/**
 * Override, use a better default breadcrumb separator.
 *
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function gsapp_breadcrumb($breadcrumb) {
  if (count($breadcrumb) > 1) {
    $breadcrumb[] = drupal_get_title();
    return '<div class="breadcrumb">'. implode(' &rsaquo; ', $breadcrumb) .'</div>';
  }
}

/**
 * Rewrite of theme_form_element() to suppress ":" if the title ends with a punctuation mark.
 */
function gsapp_form_element($element, $value) {
  // $args = func_get_args();
  //   return preg_replace('@([.!?]):\s*(</label>)@i', '$1$2', call_user_func_array('theme_form_element', $args));
	// echo '<pre>';
	// print_r($element);
	// echo '</pre>';
	
	$uniqueclass = preg_replace('/([0-9]-)/', '', $element['#id']);
	$uniqueclass = preg_replace('/(auto[0-9]map|auto[0-9][0-9]map)/', '', $uniqueclass);
	
  $output = '<div class="form-item ' . $uniqueclass . '"';
  if (!empty($element['#id'])) {
    $output .= ' id="'. $element['#id'] .'-wrapper"';
  }
  $output .= ">\n";
  $required = !empty($element['#required']) ? '<span class="form-required" title="'. t('This field is required.') .'">*</span>' : '';


  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $output .= ' <label for="'. $element['#id'] .'">'. str_replace('Street', 'Address', str_replace(':', '', t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)))) ."</label>\n";
    }
    else {
      $output .= ' <label>'. t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</label>\n";
    }
  }

	if(!strpos($element['#id'], 'edit-field-images') === false) {
		  if (!empty($element['#description'])) {
		    $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
		  }
		}

  $output .= " $value\n";

	if(!strpos($element['#id'], 'edit-field-images') === true) {
	  if (!empty($element['#description'])) {
	    $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
	  }
	}

  $output .= "</div>\n";

  return $output;
}


/**
 * Set status messages to use gsapp CSS classes.
 */
function gsapp_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    // gsapp can either call this success or notice
    if ($type == 'status') {
      $type = 'success';
    }
    $output .= "<div class=\"messages $type\">\n";
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>'. $message ."</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Override comment wrapper to show you must login to comment.
 */
function gsapp_comment_wrapper($content, $node) {
  global $user;
  $output = '';

  if ($node = menu_get_object()) {
    if ($node->type != 'forum') {
      $count = $node->comment_count; 
      $count = ($count > 0) ? $count : 'No comments';
      $output .= '<strong class="section-title">Comments ('. $count .')</strong>';
    }
  }

  $output .= '<div class="content">';
  $msg = '';
  if (!user_access('post comments')) {
    $dest = 'destination='. $_GET['q'] .'#comment-form';
    $msg = '<div id="messages"><div class="error-wrapper"><div class="messages error">'. t('Please <a href="!register">register</a> or <a href="!login">login</a> to post a comment.', array('!register' => url("user/register", array('query' => $dest)), '!login' => url('user', array('query' => $dest)))) .'</div></div></div>';
  }
  $output .= $content;
  $output .= $msg;

  return $output .'</div>';
}

/**
 * Override, use better icons, source: http://drupal.org/node/102743#comment-664157
 *
 * Format the icon for each individual topic.
 *
 * @ingroup themeable
 */
function gsapp_forum_icon($new_posts, $num_posts = 0, $comment_mode = 0, $sticky = 0) {
  // because we are using a theme() instead of copying the forum-icon.tpl.php into the theme
  // we need to add in the logic that is in preprocess_forum_icon() since this isn't available
  if ($num_posts > variable_get('forum_hot_topic', 15)) {
    $icon = $new_posts ? 'hot-new' : 'hot';
  }
  else {
    $icon = $new_posts ? 'new' : 'default';
  }

  if ($comment_mode == COMMENT_NODE_READ_ONLY || $comment_mode == COMMENT_NODE_DISABLED) {
    $icon = 'closed';
  }

  if ($sticky == 1) {
    $icon = 'sticky';
  }

  $output = theme('image', path_to_theme() . "/images/icons/forum-$icon.png");

  if ($new_posts) {
    $output = "<a name=\"new\">$output</a>";
  }

  return $output;
}

/**
 * Override, remove previous/next links for forum topics
 *
 * Makes forums look better and is great for performance
 * More: http://www.sysarchitects.com/node/70
 */
function gsapp_forum_topic_navigation($node) {
  return '';
}

/**
 * Trim a post to a certain number of characters, removing all HTML.
 */
function gsapp_trim_text($text, $length = 150) {
  // remove any HTML or line breaks so these don't appear in the text
  $text = trim(str_replace(array("\n", "\r"), ' ', strip_tags($text)));
  $text = trim(substr($text, 0, $length));
  $lastchar = substr($text, -1, 1);
  // check to see if the last character in the title is a non-alphanumeric character, except for ? or !
  // if it is strip it off so you don't get strange looking titles
  if (preg_match('/[^0-9A-Za-z\!\?]/', $lastchar)) {
    $text = substr($text, 0, -1);
  }
  // ? and ! are ok to end a title with since they make sense
  if ($lastchar != '!' && $lastchar != '?') {
    $text .= '...';
  }
  return $text;
}

/*
 .|';                 ||                      ||                 
 ||                   ||                      ||                 
'||'  .|''|, .|''|, ''||''  `||''|,  .|''|, ''||''  .|''|, ('''' 
 ||   ||  || ||  ||   ||     ||  ||  ||  ||   ||    ||..||  `'') 
.||.  `|..|' `|..|'   `|..' .||  ||. `|..|'   `|..' `|...  `...' 


                     ||`    '||`               '||            
                     ||      ||   ''            ||            
 '''|.  `||''|,  .|''||      ||   ||  `||''|,   || //`  ('''' 
.|''||   ||  ||  ||  ||      ||   ||   ||  ||   ||<<     `'') 
`|..||. .||  ||. `|..||.    .||. .||. .||  ||. .|| \\.  `...' 

process_links - scan text for links and turn them info 'footnotes'
*/
function process_links($text) {
	$count = 0;
	$text = preg_replace_callback('(<a href="(.*?)">(.*?)</a>)', 'output_new_link', $text);
	return $text;
}
// ^ process_links calls this function
function output_new_link($matches) {
  global $count;
	$count++;
	// remove punctuation from link text and make it a link id
	$link_id = preg_replace('/\W/', '', strtolower($matches[2]));
  return $matches[2] . '<a href="#' . $link_id . '" class="anchor">' . sprintf("%02d",$count) . '</a>';
}

function process_notes($text) {
	$text = preg_replace_callback('(\[(\d+)\])', 'output_note_link', $text);
	return $text;
}
// callback for process_notes
function output_note_link($matches) {
	// remove punctuation from link text and make it a link id
	$link_id = $matches[1];
  return $matches[2] . '<a href="#n' . $link_id . '" class="note-link" id="' . $link_id . '-orig">N' . $link_id . '</a>';
}

// Get the footnotes for the footer
function get_footnotes($text) {
	$regex = '(<a href="(.*?)">(.*?)</a>)';
	preg_match_all($regex, $text, $matches);
	return $matches[0];
}

// process the footnote links created by get_footnotes so we can add ids
function format_footnote_link($url) {
    /*** find the link test ***/
    preg_match('(<a href="(.*?)">(.*?)</a>)', $url, $link_text);
		$link_id = preg_replace('/\W/', '', strtolower($link_text[2])); 
		$link = '<a target="_self" href="' . $link_text[1] . '" id="' . $link_id . '">' . $link_text[2] . '</a>';
    return $link;
}

/**
* gsapp views node
*/

function gsapp_preprocess_views_view_row_node(&$vars) {
	drupal_add_js('sites/all/themes/gsapp/scripts/masonry.js');
	drupal_add_js('sites/all/themes/gsapp/scripts/scroll.js');
	drupal_add_js('sites/all/themes/gsapp/scripts/scroll-init.js');
	drupal_add_js('sites/all/themes/gsapp/scripts/jquery.sb/jquery.sb.js');
}

function gsapp_preprocess_views_view_row_node__excerpt(&$vars) {	
	$view = &$vars['view'];
	$options = &$vars['options'];
	$item = &$vars['row'];

	// Use the [id] of the returned results to determine the nid in [results]
	$result = &$vars['view']->result;
	$id = &$vars['id'];
	$node = node_load( $result[$id-1]->nid );

	$vars['node'] = $node;
}

function gsapp_preprocess_views_view_row_node__widgetheader(&$vars) {	
	$view = &$vars['view'];
	$options = &$vars['options'];
	$item = &$vars['row'];

	// Use the [id] of the returned results to determine the nid in [results]
	$result = &$vars['view']->result;
	$id = &$vars['id'];
	$node = node_load( $result[$id-1]->nid );

	$vars['node'] = $node;
}

function gsapp_preprocess_views_view_row_node__magazine(&$vars) {
	$view = &$vars['view'];
	$options = &$vars['options'];
	$item = &$vars['row'];

	// Use the [id] of the returned results to determine the nid in [results]
	$result = &$vars['view']->result;
	$id = &$vars['id'];
	$node = node_load( $result[$id-1]->nid );

	$vars['node'] = $node;
}

function gsapp_preprocess_views_view_row_node__tags(&$vars) {
	$view = &$vars['view'];
	$options = &$vars['options'];
	$item = &$vars['row'];

	// Use the [id] of the returned results to determine the nid in [results]
	$result = &$vars['view']->result;
	$id = &$vars['id'];
	$node = node_load( $result[$id-1]->nid );

	$vars['node'] = $node;
}

function gsapp_preprocess_views_view_row_node__image(&$vars) {
	$view = &$vars['view'];
	$options = &$vars['options'];
	$item = &$vars['row'];

	// Use the [id] of the returned results to determine the nid in [results]
	$result = &$vars['view']->result;
	$id = &$vars['id'];
	$node = node_load( $result[$id-1]->nid );

	$vars['node'] = $node;
}

function gsapp_preprocess_views_view__create_note(&$vars) {
	drupal_add_css('sites/all/themes/gsapp/css/scraper.css');
	drupal_add_js('sites/all/themes/gsapp/scripts/scraper.js');
	drupal_add_js('sites/all/modules/ckeditor/ckeditor/ckeditor.js?'.$ra);
	drupal_add_js('sites/all/modules/ckeditor/ckeditor.styles.js?'.$ra);
}

/**
  * Enables theme developers to include a link to the node of the same type
  * that comes immediately after the the node being currently viewed.
  *
  * A node has to be published and promoted to the front page to
  * qualify as the 'next node'. Unpublished and nodes not promoted 
  * to front page are not considered.  Access control is respected.
  *
  * Theme developers would normally use this in the template for a node.
  *    
  * @param $node
  *     node whose next node is to be found.
  * @param $next_node_text
  *   The text for the link that will be created. If no text is given
  *     then the title of the next node is used.
  * @param $prepend_text
  *     Text to be prepended to the created link. It is not a part of the link.
  * @param $append_text
  *     Text to be appended to the created link. It is not a part of the link.
  *
  */   
 function next_node($node, $next_node_text=NULL, $prepend_text=NULL, $append_text=NULL)
 {   
     $query = db_rewrite_sql("SELECT nid, title FROM {node} WHERE created > '%s' AND status=1 AND type='%s' ORDER BY created ASC LIMIT 1", "node", "nid");
     
     $result = db_query($query, $node->created, $node->type);

    $next_node = db_fetch_object($result);

     if(!$next_node_text) // If next_node_text is not specified then use the next node's title as the text for the link.
     {
         $next_node_text = '  NEXT<span class="next">  ▶</span>';   
     }
    
     if($next_node->nid!=NULL)
     {
         return $prepend_text.l($next_node_text, 'node/'.$next_node->nid, array('html' => TRUE,'title'=>'Go to the next post "'.$next_node_text.'"', 'class'=>'goto-previous-node')).$append_text;
     }
     else // There is no next node for this node...
     {
         return NULL;
     }
 }

 /**
  * Enables theme developers to include a link to the node of same type 
  * that comes immediately before the the node being currently viewed.
  *
  * A node has to be published and promoted to the front page to
  * qualify as the 'previous node'. Unpublished and nodes not promoted
  * to front page are not considered.  Access control is respected.
  *
  * Theme developers would normally use this in the template for a node.
  *    
  * @param $node
  *     node whose next node is to be found.
  * @param $previous_node_text
  *   The text for the link that will be created. If no text is given
  *     then the title of the previous node is used.
  * @param $prepend_text
  *     Text to be prepended to the created link. It is not a part of the link.
  * @param $append_text
  *     Text to be appended to the created link. It is not a part of the link.
  *
  */
 function previous_node($node, $previous_node_text=NULL, $prepend_text=NULL, $append_text=NULL)
 {   

     $query = db_rewrite_sql("SELECT nid, title FROM {node} WHERE created < '%s' AND status=1 AND type='%s' ORDER BY created DESC LIMIT 1", "node", "nid");
     
     $result = db_query($query, $node->created, $node->type);

    $previous_node = db_fetch_object($result);

     if(!$previous_node_text) // If previous_node_text is not specified then use the previous node's title as the text for the link.
     {
         $previous_node_text = '<span class="prev">◀  </span>PREV  ';       
     }
    
     if($previous_node->nid!=NULL)
     {
         return $prepend_text.l($previous_node_text, 'node/'.$previous_node->nid, array('html'=>'TRUE','title'=>'Go to the previous post "'.$previous_node_text.'"', 'class'=>'goto-previous-node')).$append_text;
     }
     else // This node does not have a previous node...
     {
         return NULL;
     }
 }


/* 
 * Figure out what to do with the images in a post.
 * To slideshow or not to slideshow?
 * Where in the post should I appear?
 */
function apply_media($node, $body, $full = false, $forRss = false) {
	$regex = "/(?:<p>[\n\t\w]*)?\[((image)\:((?:\d+-?\d*)|([\d,]+)))\](?:[\n\t\w]*<\/p>)?/mis";
	$slideshows = preg_match_all($regex,$body,$matches);
	$slideshowOutput = "";
	$matchCount = 0;
	                     
	
	$body = process_links($body);
	$body = process_notes($body);
		
	if (count($matches[0])==0) {
		if ($node->field_images[0][filename]) {
			$GLOBALS['id']++;
			if($GLOBALS['id'] == 1) {
		    ($forRss) ? create_slideshow($node,$GLOBALS['id'], 0,null,false,true) : create_slideshow($node, $GLOBALS['id']);
			}
		}
	} else {
		foreach( $matches[2] as $match) {
			$GLOBALS['id']++;
			if (strpos($matches[3][$matchCount],"-")!==false) {
				list($startIndex,$endIndex) = split("-",$matches[3][$matchCount]);
			}
			else if (strpos($matches[3][$matchCount],",")!==false) {
				$selectedIndexes = preg_split("/,/",$matches[3][$matchCount]);
				foreach ($selectedIndexes as &$ind) $ind -= 1;
				$startIndex = $selectedIndexes;
				$endIndex = null;
			}
			else { 
				$startIndex = $matches[3][$matchCount];
				$endIndex = $startIndex;
			}
		
			if (!is_array($startIndex)) $startIndex -= 1;
								
			if ($matches[2][$matchCount]=="image") {
				$slideshowOutput = create_slideshow($node, $GLOBALS['id'], $startIndex, $endIndex, true, $forRss);
			}						
			$body = str_replace($matches[0][$matchCount],$slideshowOutput,$body);
			$matchCount++;
		}
	}

	return $body;
}


function create_slideshow($node, $id, $startIndex=0,$endIndex=null,$returnOutput=false,$forRss=false) {
	ob_start();
      
	if ($node->field_images[0]['filename']) { 
		// Create slideshow if there are images
		$files = $node->field_images;
		$selectedIndexes = null;
		
		if (is_array($startIndex)) {
			//startIndex was passed as an array
			$selectedIndexes = $startIndex;
			$startIndex = $selectedIndexes[0];
			$endIndex = $selectedIndexes[count($selectedIndexes)-1] + 1;
		}
		
		// set the end index if it needs it	
		if ($endIndex==null || ($endIndex!=$startIndex+1 && $endIndex > count($files))) $endIndex = count($files);
	
			
		$slideCount = count($node->field_images);

		?>
			
		<div class="slideshow<?php if ($startIndex+1 == $endIndex) { echo " one "; } ?>" rel="<?="slideshow" . $id?>">
			<div class="slide-wrapper">
			
			<?php 
			
			if($node->field_images[0]['source'] == "default_image_upload") {
				echo $node->field_images[0]['view'];
			}
				
			global $base_url; 
			// echo $base_url;
			$countImages = 0;
			for ($value=$startIndex; $value<$endIndex; $value++) { 
				
				if ($selectedIndexes!=null && !in_array($value,$selectedIndexes)) continue;

				$countImages++;
				
				$imagecache_preset = ($full) ? 'fullscreen-image' : 'article-image_crop-enabled';
				$image_file = $base_url . '/sites/default/files/imagecache/' . $imagecache_preset . '/' . $node->type . '/' . $files[$value]['filename'];
				
				$image_file = str_replace(' ', '%20', $image_file);
				
				$size = getimagesize($image_file);
				
				echo '<div class="slide-item">';
				echo '<div class="slide-img">';
				echo '<img src="'. $image_file . '" width="' . $size[0] . '" height="' . $size[1] . '">';
				echo '</div>';
				
				
				echo '<div class="slide-text">';
				if($files[$value]['data']['title']) {
					echo '<strong class="slide-title">' . $files[$value]['data']['title'] . '</strong>';
				}
				
					echo '<div class="slide-description">';
						echo $files[$value]['data']['slideshow_image_description']['body'];
					echo '</div>';
				echo '</div>';
				echo '</div>';

						
			} // End for ?>
			
			
				
			</div> <!--/slide-wrapper-->

			<?php if($startIndex+1 != $endIndex) { ?>
			<div class="slideshow-nav">
				<div class="prev-next">
					<a href="#" class="prev">Prev</a>
					<span class="slide-count">
						<span class="current">01</span> of <span class="total"><?php echo sprintf("%02d", $countImages); ?></span>
					</span>
					<a href="#" class="next">Next</a>
				</div>


				<?php // <a href="#" class="start-slideshow">Launch Slideshow</a> ?>
			</div> <!--/slideshow-nav-->
			<?php } // end slideshow nav ?>
			
		</div> <!--/slideshow-->

		<?php
	}   
	$out = ob_get_contents();
	
	// Output fullscreen version code for footer
	/*
	$GLOBALS['fullscreen_slideshow'] .= '<div class="fullscreen clearfix slideshow' . $id . '">';
	$GLOBALS['fullscreen_slideshow'] .= '<div class="wrapper">';
	$GLOBALS['fullscreen_slideshow'] .= '<a href="#" class="slideshow-exit">Exit</a>';
	$regex = '/article-image/';
	$replaceImages = preg_replace($regex, 'fullscreen-image', $out);
	
	$thumbs = '<div class="thumbs">';
	$countImages = 0;
	for ($value=$startIndex; $value<$endIndex; $value++) { 
		if ($selectedIndexes!=null && !in_array($value,$selectedIndexes)) continue;
			$countImages++;
			$imagecache_preset = 'slide-thumb';
			$thumbs .= '<img src="' . $base_path . '/sites/default/files/imagecache/' . $imagecache_preset . '/' . $node->type . '/' . $files[$value]['filename'] . '">';
	}
	$thumbs .= '</div>';

	$regex = '(<a href="#" class="start-slideshow">(.*?)</a>)';
	$addThumbs = preg_replace($regex, $thumbs, $replaceImages);
		
	$GLOBALS['fullscreen_slideshow'] .= $addThumbs;
	$GLOBALS['fullscreen_slideshow'] .= '</div></div>';
	// end fullscreen output
	*/
	
	if ($returnOutput) {
		ob_end_clean();		
		return $out;
	}else ob_end_flush();
}

/*
* Override filter.module's theme_filter_tips() function to disable tips display.
*/
function gsapp_filter_tips($tips, $long = FALSE, $extra = '') {
  return '';
}
function gsapp_filter_tips_more_info () {
  return '';
}

/**
* Implementation of HOOK_theme().
*/
// Reset the comment form so we can modify it
function gsapp_theme() {
	
  return array(
	// 'campaignmonitor_subscribe_form' => array(                       
	// 'arguments' => array('form' => NULL),
	//   	),
	// 

	'user_login' => array(
    'template' => 'user-login', // This refers to the template "user-login.tpl.php".
    'arguments' => array('form' => NULL),
    ),
  );
}

// theme the CM form
// function gsapp_campaignmonitor_subscribe_form($form) {
// 	$form['name']['#title'] = t('Your Name');
//   	$form['email']['#value'] = t('');
// 	$form['email']['#title'] = t('Your Email');
// 	$form['Address1']['#title'] = t('Address 1');
// 	$form['submit']['#value'] = t('Subscribe');
// 	
//       
//   return drupal_render($form);
// }

function gsapp_preprocess_views_view (&$vars) {

}

function save_remote($remoteFile){
	// Local file for saving
	$filename = basename($remoteFile);
	
	$filename2 = explode('?', $filename);
	
	$localFile = "sites/default/files/notes/" . $filename2[0];

	// Time to cache in hours
	$cacheTime = 24;

	// Connection time out
	$connTimeout = 10;

	if(file_exists($localFile) && (time() - ($cacheTime * 3600) < filemtime($localFile))){
	     readfile($localFile);
	}else{
	     $url = parse_url($remoteFile);
	     $host = $url['host'];
	     $path = isset($url['path']) ? $url['path'] : '/';

	     if (isset($url['query'])) {
	          $path .= '?' . $url['query'];
	     }

	     $port = isset($url['port']) ? $url['port'] : '80';

	     $fp = @fsockopen($host, '80', $errno, $errstr, $connTimeout );

	     if(!$fp){
	          // If connection failed, return the cached file
	          if(file_exists($localFile)){
	               readfile($localFile);
	          }
	     }else{
	          // Header Info
	          $header = "GET $path HTTP/1.0\r\n";
	          $header .= "Host: $host\r\n";
	          $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6\r\n";
	          $header .= "Accept: */*\r\n";
	          $header .= "Accept-Language: en-us,en;q=0.5\r\n";
	          $header .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
	          $header .= "Keep-Alive: 300\r\n";
	          $header .= "Connection: keep-alive\r\n";
	          $header .= "Referer: http://$host\r\n\r\n";
						
	           $response = '';
	          fputs($fp, $header);
	          // Get the file content
	          while($line = fread($fp, 4096)){
	               $response .= $line;
	          }
	          fclose( $fp );

	          // Remove Header Info
	          $pos = strpos($response, "\r\n\r\n");
	          $response = substr($response, $pos + 4);
	          // echo $response;

	          // Save the file content
	          if(!file_exists($localFile)){
	               // Create the file, if it doesn't exist already
	               fopen($localFile, 'w');
	          }
	          if(is_writable($localFile)) {
	               if($fp = fopen($localFile, 'w')){
	                    fwrite($fp, $response);
	                    fclose($fp);
	               }
	          }
	     }
	}	
}


/*
 * Create animated gifs for view rollover thumbnails
 * Use GD to get create the individual images and ImageMagick to create the animated gif
 */
function animated($node, $type, $force = false, $terms) {
	// GLOBAL
	$width = 220;
	$height = 165;
	$font = 'sites/all/themes/gsapp/css/type/neutra2text-book-webfont.ttf';
	$fontsize = 15;
	$count = 1;
	$image_count = 0;
	$image_dir = 'sites/default/files/rollovers/' . $node->nid;
	$line_height = 20;
	
	// check if the image has already been created
	if(!is_dir($image_dir)) {
		mkdir($image_dir, 0777);
	}
	
	// Trying to increase site performance
	// Check if the gif has already been generated.  If it has, just return the existing file
	// Need to add a little more logic here so that gifs that weren't generated properly will regenerate
	// ie: gif doesn't include photo
	$filename = $_SERVER['DOCUMENT_ROOT'] . '/' . $image_dir . '/'. $type . '.gif';
	if (file_exists($filename) && $force == false) {
		return '/' . $image_dir . '/'. $type . '.gif';
	}

	if($type == "excerpt") {
		animated_image($node, $image_dir, $count, $image_count);
		animated_by_date($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height);
		if($node->field_images[$image_count]['filename']) {
			animated_image($node, $image_dir, $count, $image_count);
		}
		if($node->field_images[$image_count]['filename']) {
			animated_image($node, $image_dir, $count, $image_count);
		}
		animated_tags($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height, $terms);
	} elseif($type == "image") {
		animated_excerpt($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height);
		animated_by_date($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height);
		animated_tags($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height, $terms);
	} elseif($type == "tags") {
		animated_image($node, $image_dir, $count, $image_count);
		animated_excerpt($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height);
		animated_by_date($node, $image_dir, $width, $height, $font, $fontsize, $count, $line_height);
		if($node->field_images[$image_count]['filename']) {
			animated_image($node, $image_dir, $count, $image_count);
		}
		if($node->field_images[$image_count]['filename']) {
			animated_image($node, $image_dir, $count, $image_count);
		}
	}
	
	/* Create the animated gif with ImageMagick by
	 * combining the images created by the above functions
	 */
	exec("convert -delay 55 $image_dir/temp*.gif -loop 0  $image_dir/$type.gif");

	// remove unnecessary files used to create the animated gif
	$dir = $image_dir;
	$files = scandir($dir);

	foreach($files as $file) {
		if(strpos($file, '.gif')) {
			$found = strpos($file, 'temp');
			if($found !== FALSE) {
				unlink($dir . '/' . $file);
			}
		}
	}
	
	return '/' . $image_dir . '/'. $type . '.gif';
}

/* Image */
function animated_image($node, $image_dir, &$count, &$image_count) {
	
	if($node->field_images[$image_count]['filename']) {
		$image = 'http://' . $_SERVER['HTTP_HOST'] . '/sites/default/files/imagecache/article-image_crop-enabled/' . $node->type . '/' . $node->field_images[$image_count]['filename'];
	} else {
		$image = 'http://' . $_SERVER['HTTP_HOST'] . '/sites/default/files/imagecache/briefs-view/imagefield_default_images/gsapp-default.png';
	}
	
	// remove variables from url
	$remove_var = explode('?', $image);
	$image = $remove_var[0];
	
	// replace spaces
	$image = str_replace(' ', '%20', $image);
	
	
	if(strpos($image, '.jpg')) {
		$im = imagecreatefromjpeg($image);
	} elseif(strpos($image, '.jpeg')) {
		$im = imagecreatefromjpeg($image);
	} elseif(strpos($image, '.png')) {
		$im = imagecreatefrompng($image); 
	} elseif(strpos($image, '.gif')) {
		$im = imagecreatefromgif($image); 
	}
	
	if($im == null) {
		watchdog('animated_image', 'image not found');
		return;
	}
	
	// get original image size
	list($width, $height) = getimagesize($image);
	 	
	// create the container for the new image
	$image_resize = imagecreatetruecolor(220, 165);
	 	
	// resize the original and store it in $image_resize
	imagecopyresampled($image_resize, $im, 0, 0, 0, 0, 220, 165, $width, $height);

	
	if($count < 10) {
		$ncount = '0' . $count;
	} else {
		$ncount = $count;
	}
	
	imagegif($image_resize, $image_dir . '/temp' . $ncount . '.gif'); 
	imagedestroy($image_resize);

	$count++;
	watchdog("animated_image", "image $image_count created");
	$image_count++;
}

/* By & Date */
function animated_by_date($node, $image_dir, $width, $height, $font, $fontsize, &$count, $line_height) {
	$im = imagecreate($width, $height);

	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$blue = imagecolorallocate($im, 0, 137, 255);
	imagefilledrectangle($im, 0, 0, $width, $height, $blue);
	
	if(strip_tags($node->field_author[0]['value'])) { $author = $node->field_author[0]['value']; }
	
	$text = format_date($node->created, 'custom', "g:i a\nm.d.y");

	// If editor entered an author name, append it to the time/date
	if($author) { 
		$author = "\nby " . $author;
		$text .= $author;
	}

	// Write the string
	$wrap_text = wordwrap($text, 20, "\n");
	$lines = explode("\n", $wrap_text);
	// print_r($lines);

	for($i=0; $i < count($lines); $i++) {
		$tb = imagettfbbox($fontsize, 0, $font, $lines[$i]);
		$x = ceil(($width - $tb[2]) / 2); // lower left X coordinate for text
		
		if($i == 0) { 
			// calculate total text height and figure out position from the top for it to be centered
			$y = (($height - ($line_height * count($lines))) / 2) + $line_height - 5;			
		} else {
			$y += 21;
		}
		
		imagettftext($im, $fontsize, 0, $x, $y, $white, $font, $lines[$i]);
	}
	

	if($count < 10) {
		$ncount = '0' . $count;
	} else {
		$ncount = $count;
	}
	
	imagegif($im, $image_dir . '/temp' . $ncount . '.gif');
	imagedestroy($im);
	
	$count++;
	watchdog('animated_by_date', 'Created author/timestamp');
}

/* Excerpt */
function animated_excerpt($node, $image_dir, $width, $height, $font, $fontsize, &$count, $line_height) {
	$im = imagecreate($width, $height);

	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$blue = imagecolorallocate($im, 0, 137, 255);
	imagefilledrectangle($im, 0, 0, $width, $height, $blue);
	
	$excerpt = strip_tags($node->field_excerpt[0]['value']);
	
	// Some excerpts are just too long
	if (drupal_strlen($excerpt) > 105) {
		$text = drupal_substr($excerpt, 0, 105) .'...';
	} else {
		$text = $excerpt;
	}

	$wrap_text = wordwrap($text, 20, "\n");
	$lines = explode("\n", $wrap_text);

	$tb = imagettfbbox($fontsize, 0, $font, $wrap_text);
	$x = ceil(($width - $tb[2]) / 2); // lower left X coordinate for text
	$y = (($height - ($line_height * count($lines))) / 2) + $line_height - 5;	

	// Write the string
	imagettftext($im, $fontsize, 0, $x, $y, $white, $font, $wrap_text);

	if($count < 10) {
		$ncount = '0' . $count;
	} else {
		$ncount = $count;
	}
	
	imagegif($im, $image_dir . '/temp' . $ncount . '.gif');
	imagedestroy($im);
	
	$count++;
	watchdog('animated_excerpt', 'Added excerpt');
}

/* Tags */
function animated_tags($node, $image_dir, $width, $height, $font, $fontsize, &$count, $line_height, $terms) {
	
	/*
	 * When a node is updated, this image needs to be regenerated
	 * gsappforms.module handles this.  Need to pass the $node->taxonomy value to this function since
	 * the module doesn't have direct access
	 */
	if(!$terms) {
		$tags = $node->taxonomy;
	} else {
		$tags = $terms;
	}
	
	foreach ($tags as $term) {
		
		if($term->vid == 1 || $terms) {
			if(!$term->name) {
				continue;
			}
			watchdog('tag', "$term->name");
			// Create the image
			$im = imagecreate($width, $height);

			// Create some colors
			$white = imagecolorallocate($im, 255, 255, 255);
			$blue = imagecolorallocate($im, 0, 137, 255);
			imagefilledrectangle($im, 0, 0, $width, $height, $blue);

			$text = $term->name;
			
			$wrap_text = wordwrap($text, 20, "\n");
			$lines = explode("\n", $wrap_text);

			$tb = imagettfbbox($fontsize, 0, $font, $wrap_text);
			$x = ceil(($width - $tb[2]) / 2); // lower left X coordinate for text
			$y = (($height - ($line_height * count($lines))) / 2) + $line_height - 5;	

			// Write the string
			imagettftext($im, $fontsize, 0, $x, $y, $white, $font, $wrap_text);

			if($count < 10) {
				$ncount = '0' . $count;
			} else {
				$ncount = $count;
			}

			imagegif($im, $image_dir . '/temp' . $ncount . '.gif');
			imagedestroy($im);
			
			$count++;
			
		}
	}	
	watchdog('animated_tags', 'added tags');
}

/* output notes */
function output_notes($node) {
	
	echo '<div id="notes">';
			$x = 0;

			foreach($node->field_notes as $note) {
				$x++;
		
				if(strip_tags($note['value']) != null) {

					echo '<div class="note clearfix" id="n' . $x . '">';
						echo '<a href="#' . $x . '-orig" class="note-num">N' . $x . '</a>';
						echo '<div class="note-text">' . $note['value'] . '</div>';
					echo '</div>';
				}
			}
	echo '</div>';
}


function gsapp_preprocess_user_login(&$variables) {

}

function gsapp_shorten($text, $end) {
	if(strlen($text) > $end) {
		return drupal_substr($text, 0, $end) . '...';
	} else {
		return $text;
	}
}
