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

$presets['green'] = array (
  'presetname' => 'green',
  '#weight' => 4.1,
  'actions' => array (
    0 => array (
      'weight' => '0',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_colorshift',
      'data' => array (
        'RGB' => array (
          'HEX' => '08f737',
        ),
      ),
    ),
  ),
);