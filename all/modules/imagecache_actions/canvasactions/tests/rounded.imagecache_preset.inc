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

$presets['rounded'] = array (
  'presetname' => 'rounded',
  '#weight' => 3.0,
  'actions' => array (
    1 => array (
      'weight' => '0',
      'module' => 'imagecache_canvasactions',
      'action' => 'canvasactions_roundedcorners',
      'data' => array (
        'radius' => '25',
        'antialias' => true,
      ),
    ),
    2 => array (
      'weight' => '3',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_convert',
      'data' =>array (
        'format' => 'image/png',
      ),
    ),
  ),
);