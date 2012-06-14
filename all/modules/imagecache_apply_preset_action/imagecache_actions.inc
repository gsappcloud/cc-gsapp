<?php

/**
 * ImageCache Apply preset
 */
function imagecache_apply_preset_form($data = array()) {
  $path_parts = explode('/', $_GET['q']);

  $current_preset_id = $path_parts[3];

  $presets = imagecache_presets();

  $presetOptions = array();
  foreach ($presets as $preset) {
    if ($preset['presetid'] == $current_preset_id) {
      continue;
    }
    $presetOptions[$preset['presetid']] = $preset['presetname'];
  }

  $form = array();

  $form['preset'] = array(
    '#type' => 'select',
    '#title' => t('Preset'),
    '#options' => $presetOptions,
  );

  if (isset($data['preset'])) {
    $form['preset']['#default_value'] = $data['preset'];
  }

  return $form;
}

function imagecache_apply_preset_image(&$image, $data) {
  $preset_id = $data['preset'];

  $preset = imagecache_preset($preset_id);
  if (!is_array($preset)) {
    return FALSE;
  }

  foreach($preset['actions'] as $action) {
    _imagecache_apply_action($action, $image);
  }

  return TRUE;
}

function theme_imagecache_apply_preset($element) {
  $data = $element['#value'];
  $preset_id = $data['preset'];

  $preset = imagecache_preset($preset_id);
  if (!is_array($preset)) {
    return '';
  }

  return $preset['presetname'];
}