<?php
	print drupal_render($form['name']);
  print drupal_render($form['pass']);
	echo '<div class="form-item forgot-password"><a href="/user/password">Forgot your password?</a></div>';
	print drupal_render($form['form_build_id']);
  print drupal_render($form['form_id']);
  print drupal_render($form['submit']);