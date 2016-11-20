<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_ClearTwigCache
 * @version 0.1
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 * 
 * ## Changelog
 * 
 * 	+ 2016-02-22 Upgrade to AbstractPicoPlugin for Pico 1.0
 * 
 */
class mcb_ClearTwigCache extends AbstractPicoPlugin {
   public function onPageRendering(&$twig, &$twigVariables, &$templateName)
   {
      if($_SERVER['QUERY_STRING'] == 'clear')
         $twig->clearCacheFiles();
   }

   /* debug
   public function onPageRendered(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}

?>