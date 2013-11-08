<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_Random
 * @version 0.1 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>

 */
class mcb_Random {

   private $path;
   private $alt;
   private $random;

   public function config_loaded(&$settings)
   {
      $this->path   = isset($settings['mcb_random_img_path']) ? $settings['mcb_random_img_path'] : $settings['base_url'] . "/content/images";
      $this->alt    = &$settings['mcb_random_img_alt'];
      $this->random = &$settings['mcb_random'];

      if(!isset($settings['mcb_random_img_alt']))
         $settings['mcb_random_img_alt'] = "";

      foreach ($this->random as &$value) {
         $value = split(";", $value);

         $alt = isset($value[3]) ? $value[3] : $this->alt;

         $value = array('txt1' => @$value[0],
                        'txt2' => @$value[1],
                        'img' => isset($value[2]) ?

                                 ("<img src=\"".$this->path.DIRECTORY_SEPARATOR.$value[2]."\"".
                                       ($alt!="" ? " alt=\"$alt\" title=\"$alt\"" : "").">")
                                 :"", );
      }
   }

   public function before_render(&$twig_vars, &$twig)
   {
      $num =  rand (0, count($this->random)-1);
      $twig_vars['random'] = $this->random[$num];
   }
   /* debug
   public function after_render(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}

?>