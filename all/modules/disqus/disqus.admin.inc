<?php

/**
 * Menu callback; Displays the administration settings for Disqus.
 */
function disqus_admin_settings() {
  $form = array();
  $form['disqus_domain'] = array(
    '#type' => 'textfield',
    '#title' => t('Shortname'),
    '#description' => t('The domain that you registered Disqus with. If you registered http://example.disqus.com, you would enter "example" here.'),
    '#default_value' => variable_get('disqus_domain', ''),
  );
  // Visibility settings.
  $form['visibility'] = array(
    '#type' => 'fieldset',
    '#title' => t('Visibility'),
    '#collapsed' => FALSE,
    '#collapsible' => TRUE,
  );
  $form['visibility']['disqus_nodetypes'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Node Types'),
    '#description' => t('Apply comments to only the following node types.'),
    '#default_value' => variable_get('disqus_nodetypes', array()),
    '#options' => node_get_types('names'),
  );
  $form['visibility']['disqus_location'] = array(
    '#type' => 'select',
    '#title' => t('Location'),
    '#description' => t('Display the Disqus comments in the given location. When "Block" is selected, the comments will appear in the <a href="@disquscomments">Disqus Comments block</a>. The "Injected Variable" option will provide a variable for precise placement of the comments.', array('@disquscomments' => url('admin/build/block'))),
    '#default_value' => variable_get('disqus_location', 'content_area'),
    '#options' => array(
      'content_area' => t('Content Area'),
      'block' => t('Block'),
      'variable' => t('Injected Variable'),
    ),
  );
  $form['visibility']['disqus_weight'] = array(
    '#type' => 'select',
    '#title' => t('Weight'),
    '#description' => t('When the comments are displayed in the content area, you can change the position at which they will be shown.'),
    '#default_value' => variable_get('disqus_weight', 50),
    '#options' => drupal_map_assoc(array(-100, -75, -50, -25, 0, 25, 50, 75, 100)),
  );
  // Behavior settings.
  $form['behavior'] = array(
    '#type' => 'fieldset',
    '#title' => t('Behavior'),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
  );
  $form['behavior']['disqus_userapikey'] = array(
    '#type' => 'textfield',
    '#title' => t('User API Key'),
    '#description' => t('The API key of the administrator account on Disqus. You can get yours <a href="@key">here</a>.', array('@key' => 'http://disqus.com/api/get_my_key/')),
    '#default_value' => variable_get('disqus_userapikey', ''),
  );
  $form['behavior']['disqus_localization'] = array(
    '#type' => 'checkbox',
    '#title' => t('Localization support'),
    '#description' => t("When enabled, overrides the language set by Disqus with the language provided by the site."),
    '#default_value' => variable_get('disqus_localization', FALSE),
  );
  $form['behavior']['disqus_inherit_login'] = array(
    '#type' => 'checkbox',
    '#title' => t('Inherit User Credentials'),
    '#description' => t("When enabled and a user is logged in, the Disqus 'Post as Guest' login form will be pre-filled with the user's name and email address."),
    '#default_value' => variable_get('disqus_inherit_login', TRUE),
  );
  $form['behavior']['disqus_developer'] = array(
    '#type' => 'checkbox',
    '#title' => t('Testing'),
    '#description' => t('When enabled, uses the <a href="http://disqus.com/help/#faq-14">disqus_developer</a> flag to tell Disqus that you are in a testing environment. Threads will not display on the public community page with this set.'),
    '#default_value' => variable_get('disqus_developer', FALSE),
  );
  // Advanced settings.
  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced'),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
    '#description' => t('Use these settings to configure the more advanced uses of Disqus. You can find ore information about these in the <a href="@applications">Applications</a> section of Disqus.', array(
      '@applications' => 'http://disqus.com/api/applications/',
    )),
  );
  $form['advanced']['disqus_publickey'] = array(
    '#type' => 'textfield',
    '#title' => t('Public Key'),
    '#default_value' => variable_get('disqus_publickey', ''),
  );
  $form['advanced']['disqus_secretkey'] = array(
    '#type' => 'textfield',
    '#title' => t('Secret Key'),
    '#default_value' => variable_get('disqus_secretkey', ''),
  );
  $form['advanced']['disqus_sso'] = array(
    '#type' => 'checkbox',
    '#title' => t('Single Sign-On'),
    '#description' => t('Provide <a href="@sso">Single Sign-On</a> access to your site.', array(
      '@sso' => 'http://disqus.com/api/sso/',
    )),
    '#default_value' => variable_get('disqus_sso', FALSE),
  );
  return system_settings_form($form);
}

/**
 * Menu callback; Automatically closes the window after the user logs in.
 */
function disqus_closewindow() {
  drupal_add_js('window.close();', 'inline');
  return t('Thank you for logging in. Please close this window, or <a href="@clickhere">click here</a> to continue.', array('@clickhere' => 'javascript:window.close();'));
}
