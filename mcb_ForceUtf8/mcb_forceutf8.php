<?php

/**
 * @see README.mb for further details
 * 
 * @package Pico
 * @subpackage mcb ForceUtf8
 * @version 0.1
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 * 
 * ## Changelog
 * 
 * 	+ 2016-02-22 Upgrade to AbstractPicoPlugin for Pico 1.0
 * 
 */
class mcb_ForceUTF8 extends AbstractPicoPlugin {
   
   public function onPageRendered(&$output)
   {
      header('Content-Type: text/html; charset=utf-8');
   }
}
