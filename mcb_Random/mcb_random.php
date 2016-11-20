<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_Random
 * @version 0.2
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 * 	+ 2015-11-07 Upgrade to AbstractPicoPlugin for Pico 1.0
 */
class mcb_Random extends AbstractPicoPlugin {

   private $path;
   private $alt;
   private $random;
	private $test;

   public function onConfigLoaded(&$settings)
   {
      $this->path   = isset($settings['mcb_random_img_path']) ? $settings['mcb_random_img_path'] : $settings['base_url'] . "/content/images";
      $this->alt    = &$settings['mcb_random_img_alt'];
      $this->random = &$settings['mcb_random'];

      if(!isset($settings['mcb_random_img_alt']))
         $settings['mcb_random_img_alt'] = "";

      foreach ($this->random as &$value) {
         $value = explode(";", $value);

         $alt = isset($value[3]) ? $value[3] : $this->alt;

         $value = array('txt1' => @$value[0],
                        'txt2' => @$value[1],
                        'img' => isset($value[2]) ?

                                 ("<img src=\"".$this->path.DIRECTORY_SEPARATOR.$value[2]."\"".
                                       ($alt!="" ? " alt=\"$alt\" title=\"$alt\"" : "").">")
                                 :"", );
      }
   }

   public function onPageRendering(&$twig, &$twig_vars, &$templateName)
   {
   	$this->test[] = &$twig_vars;
      $num =  rand (0, count($this->random)-1);
      $twig_vars['random'] = $this->random[$num];
   }
   /* debug
   public function onPageRendered(&$output)
   {
      $output = $output . "<pre style=\"background-color:silver;\">".htmlentities(print_r($this->test,1))."</pre>";
   }*/
}

?>