<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_HtmlSitemap
 * @version 0.4
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 * 
 * ## Changelog
 * 
 * 	+ 2016-02-22 
 * 		+ Bugfix for untitled pages are not shown; Now shown as "*Untitled page n"
 *   		+ Upgrade to AbstractPicoPlugin for Pico 1.0
 * 
 */
class mcb_HtmlSitemap extends AbstractPicoPlugin {

	private $url_is_sitemap = false;
	private $hidden_folder  = "hidden";
	private $show_hidden    = false;
	private $content;

	public function onConfigLoaded(&$config)
	{
		if(isset($config['mcb_HtmlSitemap_hidden_folder'])) $this->hidden_folder = $config['mcb_HtmlSitemap_hidden_folder'];
		if(isset($config['mcb_HtmlSitemap_show_hidden'  ])) $this->show_hidden   = $config['mcb_HtmlSitemap_show_hidden'  ];
	}

	public function onRequestUrl(&$url)
	{
		$this->url_is_sitemap = strpos(strtolower($url), 'sitemap') !== false;
	}

	public function onPagesLoaded(&$pages, &$currentPage, &$previousPage, &$nextPage)
	{
		if(!$this->url_is_sitemap)
			return;

		global $config;
      $start    = strlen($config['base_url']);
      $base_url = $config['base_url'];
		static $tmp = 1;
      foreach($pages as &$page)
      {
          $key = substr ($page['url'], $start);

          if(substr($key, strlen($key)-1) == DIRECTORY_SEPARATOR)
             $key = substr($key, 0, strlen($key)-1);

			 if(stripos($key, $this->hidden_folder) === false)
		    	$p[$key] = $page['title']==""? "*Untitled page ".$tmp++ : $page['title'];
			 else
			 	$p2[$key] = $page['title']==""? "*Untitled page ".$tmp++ : $page['title'];
      }

		ksort ( $p, SORT_STRING);

		if($this->show_hidden)
		{
			ksort ( $p2, SORT_STRING);
			$p = array_merge($p, $p2);
		}

      $sitemap = "<ul>";
		
		$this->tmp[] = $p;
		
		foreach($p as $url => $title)
		{
			$url = $url == "0" ? "" : $url;
			$level = count(explode( "/", $url));
			$sitemap .= "<li class=\"level$level\"> <a href=\"$url\">$title</a></li>\n";
		}

		$this->content .= $sitemap . "</ul>";
	}

	public function on404ContentLoaded(&$rawContent)
	{
	   if(!$this->url_is_sitemap)
         return;

		$rawContent = "";
	}

	public function onContentParsed(&$content)
	{
		$this->content = &$content;
	}
	/* debug
	public function onPageRendered(&$output)
	{
		$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
	}*/
}
?>