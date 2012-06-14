<?php
// $ID:  $
/**
 * @file
 *   Test imagecache preset.
 * 
 * Created on Dec 29, 2009
 *
 * @author 'dman' Dan Morrison http://coders.co.nz/
 */

$presets['bright'] = array (
  'presetname' => 'bright',
  '#weight' => 1,
  'actions' => array (
    0 => array (
      'weight' => '0',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_brightness',
      'data' => array (
        'filter_arg1' => 150,
      ),
    ),
  ),
);