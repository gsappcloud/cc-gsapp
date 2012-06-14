<?php
// $ID:  $
/**
 * @file
 *   Test imagecache preset.
 * 
 * Created on Jan, 2011
 *
 * @author 'dman' Dan Morrison http://coders.co.nz/
 */

$presets['inverse'] = array (
  'presetname' => 'inverse',
  '#weight' => 1,
  'actions' => array (
    0 => array (
      'weight' => '0',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_inverse',
      'data' => array (
      ),
    ),
  ),
);