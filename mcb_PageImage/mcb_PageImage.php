<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_PageImage
 * @version 0.1 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_PageImage {

   private $image;
   private $thumbnail;
   private $cdir; // content dir
   // defaults
   private $ext = ".png";
   private $postfix = "_th";
   private $class = "";

   public function __construct()
   {
      $this->cdir = str_replace(ROOT_DIR, "", CONTENT_DIR);
   }

   public function config_loaded(&$settings)
   {
      if(isset($settings['mcb_pageimage_ext'    ])) $this->ext     = $settings['mcb_pageimage_ext'];
      if(isset($settings['mcb_pageimage_postfix'])) $this->postfix = $settings['mcb_pageimage_postfix'];
      if(isset($settings['mcb_pageimage_class'  ])) $this->class   = " class=\"".$settings['mcb_pageimage_class']."\"";
   }
   public function get_page_data(&$data, $page_meta)
   {
      global $config;
      $data['img'] = $data['img_tag'] = $data['thmb'] = $data['thmb_tag'] = "";

      $file = str_replace($config['base_url'] .'/', "", $data['url']);
      if($file=="" || $file[strlen($file)-1] == DIRECTORY_SEPARATOR)
         $file .= "index";

      if(file_exists(CONTENT_DIR . $file.$this->ext))
      {
         $data['img']          = $config['base_url'] .DIRECTORY_SEPARATOR. $this->cdir.str_replace(" ", "%20", $file).$this->ext;
         $data['img_tag']      = "<img src=\"".$data['img']."\" alt=\"".$data['title']."\" title=\"".$data['title']."\"$this->class/>";
      }
      if(file_exists(CONTENT_DIR . $file.$this->postfix.$this->ext))
      {
         $data['thmb']          = $config['base_url'] .DIRECTORY_SEPARATOR. $this->cdir.str_replace(" ", "%20", $file).$this->postfix.$this->ext;
         $data['thmb_tag']      = "<img src=\"".$data['thmb']."\" alt=\"Thumbnail\" title=\"".$data['title']."\"$this->class/>";
      }
   }
   /* debug
    public function after_render(&$output)
    {
        $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
    }*/
}
