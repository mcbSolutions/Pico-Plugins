<?php

/**
 * @see README.mb for further details
 * 
 * @package Pico
 * @subpackage mcb PhpInfo
 * @version 0.0
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_PhpInfo {
   
   private $url_is_phpinfo;
   
   public function request_url(&$url)
   {
      $this->url_is_phpinfo = strtolower(str_replace(" ", "", $url)) == 'phpinfo';
   }
   
   public function after_load_content(&$file, &$content)
   {
      if(!$this->url_is_phpinfo)
         return;
      
      // get the info
      ob_start();
      phpinfo();
      $phpinfo = ob_get_contents();
      ob_end_clean();
      
      // modify the div
      $dom = DOMDocument::loadHTML($phpinfo);
      $div = $dom->getElementsByTagname('div')->item(0);
      $div->removeAttribute('class');
      $div->setAttribute('id', 'phpinfo');

      $content = $dom->saveHTML($div);
   }
   /* debug
   public function after_render(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   } */
}
