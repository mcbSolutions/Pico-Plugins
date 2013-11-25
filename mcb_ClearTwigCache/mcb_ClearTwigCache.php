<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_ClearTwigCache
 * @version 0.0 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>

 */
class mcb_ClearTwigCache {
   public function before_render(&$twig_vars, &$twig, &$template)
   {
      if($_SERVER['QUERY_STRING'] == 'clear')
         $twig->clearCacheFiles();
   }
}

?>