<?php

/**
 * @see mcb_Random.mb for further details
 *
 * @author Michael Büchel
 * @link http://www.mcbSolutions.at/
 * @license http://opensource.org/licenses/MIT
 */
class mcb_Random {

   private $pluginValues;
   
   public function config_loaded(&$settings)
   {
      global $config;
      
      
      if(!isset($config['mcb_random_img_alt']))
         $config['mcb_random_img_alt'] = "";
      
      foreach ($config['mcb_random'] as &$value) {
         $value = split(";", $value);
         
         $alt = isset($value[3]) ? $value[3] : $config['mcb_random_img_alt'];
         
         $value = array('txt1' => @$value[0], 
                        'txt2' => @$value[1], 
                        'img' => isset($value[2]) ?
                        
                                 ("<img src=\"".$config['mcb_random_img_path']."/".$value[2]."\"".
                                       ($alt!="" ? " alt=\"$alt\" title=\"$alt\"" : "").">")
                                 :"", );
      }
   }
   
   public function before_render(&$twig_vars, &$twig)
   {
      global $config;
      $num =  rand (0, count($config['mcb_random'])-1);
      $twig_vars['random'] = $config['mcb_random'][$num];
   }
   /* debug
   public function after_render(&$output)
   {
        global $config;
        $output = $output . "<pre>".htmlentities(print_r($config['mcb_random'],1))."</pre>";
   }*/
}

?>