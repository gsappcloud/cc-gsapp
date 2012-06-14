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

$presets['watermark_50'] = array (
  'presetname' => 'watermark_50',
  '#weight' => 4.1,
  'actions' => array (
    0 => array (
      'weight' => '0',
      'module' => 'imagecache_canvasactions',
      'action' => 'canvasactions_file2canvas',
      'data' => array (
        'xpos' => 'right+20',
        'ypos' => 'bottom',
        'alpha' => '50',
        'path' => 'misc/druplicon.png',
      ),
    ),
  ),
);