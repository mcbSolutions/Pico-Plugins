<?php

/**
 * @see README.mb for further details
 * 
 * @package Pico
 * @subpackage mcb ForceUtf8
 * @version 0.0
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_ForceUTF8 {
   
   public function after_render(&$output)
   {
      header('Content-Type: text/html; charset=utf-8');
   }
}
