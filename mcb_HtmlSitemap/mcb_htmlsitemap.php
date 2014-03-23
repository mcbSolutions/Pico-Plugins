<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_HtmlSitemap
 * @version 0.3
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_HtmlSitemap {

	private $url_is_sitemap = false;
	private $hidden_folder  = "hidden";
	private $show_hidden    = false;
	private $content;

	public function config_loaded(&$settings)
	{
		if(isset($settings['mcb_HtmlSitemap_hidden_folder'])) $this->hidden_folder = $settings['mcb_HtmlSitemap_hidden_folder'];
		if(isset($settings['mcb_HtmlSitemap_show_hidden'  ])) $this->show_hidden   = $settings['mcb_HtmlSitemap_show_hidden'  ];
	}

	public function request_url(&$url)
	{
		$this->url_is_sitemap = strpos(strtolower($url), 'sitemap') !== false;
	}

	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page)
	{
		if(!$this->url_is_sitemap)
			return;

		global $config;
      $start    = strlen($config['base_url'])+1;
      $base_url = $config['base_url'];
      foreach($pages as &$page)
      {
          $key = substr ($page['url'], $start);

          if(substr($key, strlen($key)-1) == DIRECTORY_SEPARATOR)
             $key = substr($key, 0, strlen($key)-1);

			 if(stripos($key, $this->hidden_folder) === false)
		    	$p[$key] = $page['title'];
			 else
			 	$p2[$key] = $page['title'];
      }

		ksort ( $p, SORT_STRING);

		if($this->show_hidden)
		{
			ksort ( $p2, SORT_STRING);
			$p = array_merge($p, $p2);
		}

      $sitemap = "<ul>";
		foreach($p as $url => $title)
		{
			$url = $url == "0" ? "" : $url;
			$level = count(explode( "/", $url));
			$sitemap .= "<li class=\"level$level\"> <a href=\"$base_url/$url\">$title</a></li>\n";
		}

		$this->content .= $sitemap . "</ul>";
	}

	public function after_404_load_content(&$file, &$content)
	{
	   if(!$this->url_is_sitemap)
         return;

		$content = "";
	}

	public function after_parse_content(&$content)
	{
		$this->content = &$content;
	}
	/* debug
	public function after_render(&$output)
	{
		$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
	}*/
}
?>