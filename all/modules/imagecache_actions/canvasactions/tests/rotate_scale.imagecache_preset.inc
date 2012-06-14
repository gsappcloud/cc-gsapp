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

$presets['rotate_scale'] = array (
  'presetname' => 'rotate_scale',
  '#weight' => 1.2,

  'actions' => array (
    1 => array (
      'weight' => '1',
      'module' => 'imagecache',
      'action' => 'imagecache_rotate',
      'data' => array (        
        'degrees' => '15',
        'random' => 0,
        'bgcolor' => '',
      ),
    ),
    2 => array (
      'weight' => '2',
      'module' => 'imagecache',
      'action' => 'imagecache_scale',
      'data' => array (
        'width' => '',
        'height' => '150',
        'upscale' => TRUE,
      ),
    ),
  ),
);