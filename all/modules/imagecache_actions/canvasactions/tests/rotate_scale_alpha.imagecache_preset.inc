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

$presets['rotate_scale_alpha'] = array (
  'presetname' => 'rotate_scale_alpha',
  '#weight' => 1.6,

  'actions' => array (
    1 => array (
      'weight' => '1',
      'module' => 'imagecache',
      'action' => 'imagecache_rotate',
      'data' => array (        
        'degrees' => '65',
        'random' => 0,
        'bgcolor' => '',
      ),
    ),
    /*
     * imageapi resize is NOT alpha-safe. This test case proves the bug.
     * A work-around is to change format before resizing.
    2 => array (
      'weight' => '2',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_convert',
      'data' => array (
        'format' => 'image/png',
      ),
    ),
    */
    3 => array (
      'weight' => '3',
      'module' => 'imagecache',
      'action' => 'imagecache_scale',
      'data' => array (
        'width' => '',
        'height' => '150',
        'upscale' => TRUE,
      ),
    ),
    4 => array (
      'weight' => '4',
      'module' => 'imagecache_coloractions',
      'action' => 'imagecache_convert',
      'data' => array (
        'format' => 'image/png',
      ),
    ),

  ),
);