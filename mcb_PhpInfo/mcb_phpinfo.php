<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb PhpInfo
 * @version 0.5
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 *    + 2016-02-22 Upgrade to AbstractPicoPlugin for Pico 1.0
 *    + 2016-11-20 Added disabling mcb_TableOfContent for this plugin
 */
class mcb_PhpInfo extends AbstractPicoPlugin {

	private $url_is_phpinfo = false;
	private $use_section    = true;
	private $mcb_toc        = NULL;
	private $mcb_tocEnable  = false;

   private $info = array(
         INFO_GENERAL => 'General',
         INFO_CREDITS => 'Credits',
         INFO_CONFIGURATION => 'Configuration',
         INFO_MODULES => 'Modules',
         INFO_ENVIRONMENT => 'Environment',
         INFO_VARIABLES => 'Variables',
         INFO_LICENSE => 'License',
         INFO_ALL => 'All',
      );
   private $credit = array(
         CREDITS_GENERAL => 'General',
//       CREDITS_FULLPAGE => 'Full page',
         CREDITS_DOCS => 'Docs',
         CREDITS_MODULES => 'Modules',
         CREDITS_GROUP => 'Group',
         CREDITS_SAPI => 'Sapi',
         CREDITS_ALL => 'All',
      );

	public function onConfigLoaded(&$config)
   {
      if(isset($config['mcb_PhpInfo_use_section']))
      	$this->use_section = &$config['mcb_PhpInfo_use_section'];
	}

	public function onRequestUrl(&$url)
	{
      $this->url_is_phpinfo = strpos(strtolower($url), 'phpinfo') !== false;

      if($this->url_is_phpinfo)
      {
         try
         {
            $this->mcb_toc = $this->getPico()->getPlugin("mcb_TableOfContent");
         }
         catch(RuntimeException $e)
         {
            // not installed
         }

         if(isset($this->mcb_toc) && $this->mcb_toc->isEnabled())
         {
            $this->mcb_tocEnable = true;
            $this->mcb_toc->setEnabled(false);
         }
      }
	}

 	public function on404ContentLoaded(&$rawContent)
 	{
 	   if(!$this->url_is_phpinfo)
         return;

		$rawContent = "";
 	}

	public function onContentLoaded(&$rawContent)
	{
      if(!$this->url_is_phpinfo)
         return;

		if($this->use_section)
		{
			$info = (array_key_exists(intval(@$_GET['info'  ]), $this->info  ) === false) ? INFO_ALL        : intval(@$_GET['info'  ]);
			$cred = (array_key_exists(intval(@$_GET['credit']), $this->credit) === false) ? CREDITS_GENERAL : intval(@$_GET['credit']);

 	      $rawContent .= "<ul class=\"nav nav-tabs nav-justified\">\n";
	      foreach ($this->info as $key => $value)
	      {
	         if($key==INFO_CREDITS) {
	            if($key==$info) $rawContent .= "<li class=\"active dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">$value <span class=\"caret\"></span></a>";
	            else            $rawContent .= "<li class=\"       dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">$value <span class=\"caret\"></span></a>";

	            $rawContent .= "<ul class=\"dropdown-menu\" role=\"menu\">\n";
	            foreach ($this->credit as $ck => $cv) {
	               if($ck==$cred) $rawContent .= "<li class=\"active\"><a>$cv</a></li>\n";
	               else           $rawContent .= "<li><a href=\"?PhpInfo&info=$key&amp;credit=$ck\">$cv</a></li>\n";
	            }
	            $rawContent .= "</ul></li>\n";
	         } else
				{
	         	if($key==$info) $rawContent .= "<li class=\"active\"><a                            >$value</a></li>\n";
	         	else            $rawContent .= "<li                 ><a href=\"?PhpInfo&info=$key\">$value</a></li>\n";
				}
	      }
	      $rawContent .= "</ul>\n";
      } else {
      	$info = INFO_ALL;
      }

      if($info == INFO_CREDITS) $rawContent .= $this->phpCredit($cred);
      else                      $rawContent .= $this->phpInfo($info);

		$this->tmp = $rawContent;
   }

   private function phpInfo($info = INFO_ALL)
   {
      // get the info
      ob_start();
      phpinfo($info);
      $phpinfo = ob_get_contents();
      ob_end_clean();

      $this->tmp = $phpinfo;

      // modify the div
      // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
      $dom = new DOMDocument();
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $phpinfo);
      $div = $dom->getElementsByTagname('div')->item(0);
      $div->removeAttribute('class');
      $div->setAttribute('id', 'phpinfo');

      $this->tmp = $dom->saveHTML($div);

      return $dom->saveHTML($div);
   }

   private function phpCredit($what = CREDITS_ALL)
   {
      $what = $what & ~CREDITS_FULLPAGE;

      // get the info
      ob_start();
      phpcredits($what);
      $phpinfo = ob_get_contents();
      ob_end_clean();

      // modify the div
      // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
      $dom = new DOMDocument();
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . "<div id=\"phpinfo\">$phpinfo</div>");
      $div = $dom->getElementsByTagname('div')->item(0);

      $caption = $dom->getElementsByTagname('h1')->item(0);
      $caption->parentNode->removeChild($caption);

      return $dom->saveHTML($div);
   }

   public function onPageRendering(&$twig, &$twigVariables, &$templateName)
   {
      if(!$this->url_is_phpinfo)
         return;

      if($twigVariables['meta']['title'] == '') $twigVariables['meta']['title'] = "PHP Info";
   }

   public function onPageRendered(&$output)
   {
      if($this->url_is_phpinfo && $this->mcb_tocEnable && isset($this->mcb_tocEnable))
         $this->mcb_toc->setEnabled(true);

      unset($this->mcb_toc);

      //debug
      //$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this->tmp ,1))."</pre>";
   }
}
