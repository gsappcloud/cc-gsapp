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

$presets['rotate_alpha_gif'] = array (
  'presetname' => 'rotate_alpha_gif',
  '#weight' => 1.5,

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
    3 => array (
      'weight' => '3',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_convert',
      'data' => array (
        'format' => 'image/gif',
      ),
    ),
  ),
);