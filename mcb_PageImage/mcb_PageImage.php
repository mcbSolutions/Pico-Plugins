<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_PageImage
 * @version 0.2
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 * 	+ 2016-11-07 Upgrade to AbstractPicoPlugin for Pico 1.0
 */
class mcb_PageImage extends AbstractPicoPlugin {

   private $image;
   private $thumbnail;
   private $contenDir;
   // defaults
   private $ext = ".png";
   private $postfix = "_th";
   private $class = "";
   private $classTh = "";

   public function onConfigLoaded(&$realConfig)
   {
      if(isset($realConfig['mcb_pageimage_ext'    ])) $this->ext     = $realConfig['mcb_pageimage_ext'];
      if(isset($realConfig['mcb_pageimage_postfix'])) $this->postfix = $realConfig['mcb_pageimage_postfix'];
      if(isset($realConfig['mcb_pageimage_class'  ])) $this->class   = " class=\"".$realConfig['mcb_pageimage_class']."\"";
      if(isset($realConfig['mcb_pageimage_classth'])) $this->classTh = " class=\"".$realConfig['mcb_pageimage_classth']."\"";

	   $this->contenDir = '../'.str_replace($this->getRootDir(), "", $realConfig['content_dir']);
   }

   public function onSinglePageLoaded(&$pageData)
   {
      $contentDir = $this->getPico()->getConfig();
		$contentDir = $contentDir['content_dir'];
      $pageData['img'] = $pageData['img_tag'] = $pageData['thmb'] = $pageData['thmb_tag'] = "";

      $file = $pageData['id'];
      if($file=="" || $file[strlen($file)-1] == DIRECTORY_SEPARATOR)
         $file .= "index";

      if(file_exists($contentDir.$file.$this->ext))
      {
         $pageData['img']          = $this->contenDir.str_replace(" ", "%20", $file).$this->ext;
         $pageData['img_tag']      = "<img src=\"".$pageData['img']."\" alt=\"".$pageData['title']."\" title=\"".$pageData['title']."\"$this->class/>";
      }
      if(file_exists($contentDir.$file.$this->postfix.$this->ext))
      {
         $pageData['thmb']          = $this->contenDir.str_replace(" ", "%20", $file).$this->postfix.$this->ext;
         $pageData['thmb_tag']      = "<img src=\"".$pageData['thmb']."\" alt=\"Thumbnail\" title=\"".$pageData['title']."\"$this->classTh/>";
      }
   }
   /* debug
    public function onPageRendered(&$output)
    {
        $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
    }*/
}
