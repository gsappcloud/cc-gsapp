<?php
// $ID:  $
/**
 * @file
 *   Test imagecache preset.
 * 
 * Created on May 29, 2011
 *
 * @author 'sydneyshan' Shannon Murdoch http://enigmadigital.net.au/
 */

$presets['overlay'] = array (
  'presetname' => 'overlay',
  '#weight' => 4.1,
  'actions' => array (
    0 => array (
      'weight' => '0',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_coloroverlay',
      'data' => array (
        'RGB' => array (
          'HEX' => 'e2db6a',
        ),
      ),
    ),
  ),
);