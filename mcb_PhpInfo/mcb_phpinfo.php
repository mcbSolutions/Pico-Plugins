<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb PhpInfo
 * @version 0.4
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_PhpInfo {

	private $url_is_phpinfo;
	private $use_section = false;

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
   public function config_loaded(&$settings)
   {
      if(isset($settings['mcb_use_section']))
      	$this->use_section = &$settings['mcb_use_section'];
	}
	public function request_url(&$url)
	{
      $this->url_is_phpinfo = strpos(strtolower($url), 'phpinfo') !== false;
	}

	public function after_404_load_content(&$file, &$content)
	{
	   if(!$this->url_is_phpinfo)
         return;

		$content = "";
	}

	public function after_load_content(&$file, &$content)
	{
      if(!$this->url_is_phpinfo)
         return;


		if($this->use_section) {
	      $info = isset($_GET['info'  ]) ? $_GET['info'  ] : INFO_ALL;
	      $cred = isset($_GET['credit']) ? $_GET['credit'] : CREDITS_GENERAL;

	      $content .= '<ul class="nav nav-tabs nav-justified">';
	      foreach ($this->info as $key => $value) {
	         if($key==INFO_CREDITS) {
	            if($key==$info) $content .= "<li class=\"active dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">$value <span class=\"caret\"></span></a>";
	            else            $content .= "<li class=\"       dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">$value <span class=\"caret\"></span></a>";

	            $content .= '<ul class="dropdown-menu" role="menu">';
	            foreach ($this->credit as $ck => $cv) {
	               if($ck==$cred) $content .= "<li class=\"active\"><a>$cv</a></li>";
	               else           $content .= "<li><a href=\"?info=$key&amp;credit=$ck\">$cv</a></li>";
	            }
	            $content .= '</ul></li>';
	         } else
	         if($key==$info) $content .= "<li class=\"active\"><a                    >$value</a></li>";
	         else            $content .= "<li                 ><a href=\"?info=$key\">$value</a></li>";
	      }
	      $content .= '</ul>';
      } else {
      	$info = INFO_ALL;
      }

      if($info == INFO_CREDITS) $content .= $this->phpCredit($cred);
      else                      $content .= $this->phpInfo($info);
   }

   private function phpInfo($info = INFO_ALL)
   {
      // get the info
      ob_start();
      phpinfo($info);
      $phpinfo = ob_get_contents();
      ob_end_clean();

      // modify the div
      // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
      $dom = DOMDocument::loadHTML('<?xml encoding="utf-8" ?>' . $phpinfo);
      $div = $dom->getElementsByTagname('div')->item(0);
      $div->removeAttribute('class');
      $div->setAttribute('id', 'phpinfo');

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
      $dom = DOMDocument::loadHTML('<?xml encoding="utf-8" ?>' . "<div id=\"phpinfo\">$phpinfo</div>");
      $div = $dom->getElementsByTagname('div')->item(0);

      $caption = $dom->getElementsByTagname('h1')->item(0);
      $caption->parentNode->removeChild($caption);

      return $dom->saveHTML($div);
   }
   public function before_render(&$twig_vars, &$twig, &$template)
   {
      if(!$this->url_is_phpinfo)
         return;

      if($twig_vars['meta']['title'] == '') $twig_vars['meta']['title'] = "PHP Info";
   }
   /* debug
   public function after_render(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}
