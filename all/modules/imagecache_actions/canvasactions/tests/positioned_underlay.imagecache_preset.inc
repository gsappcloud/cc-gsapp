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

$presets['positioned_underlay'] = array (
  'presetname' => 'positioned_underlay',
  '#weight' => 4.4,
  'actions' => array (
    0 => array (
      'module' => 'imagecache',
      'action' => 'imagecache_scale',
      'weight' => '0',
      'data' => array (
        'width' => '200',
        'height' => '',
        'upscale' => 0,
      ),
    ),
    1 => array (
      'module' => 'imagecache_canvasactions',
      'action' => 'canvasactions_canvas2file',
      'weight' => '1',
      'data' => array (
        'xpos' => '50',
        'ypos' => 'bottom+50',
        'alpha' => '100',
        'path' => "$filepath/shiny-bg.png",
        'dimensions' => 'background',
      ),
    ),
  ),
);