<?php
/**
 * @see README.mb for further details
 * 
 * @package Pico
 * @subpackage mcb_HtmlSitemap
 * @version 0.0 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_HtmlSitemap {

	private $url_is_sitemap = false;
	private $content;

	public function request_url(&$url)
	{
		$this->url_is_sitemap = $url == 'sitemap' || $url == 'Sitemap';
	}
	
	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page)
	{
		if(!$this->url_is_sitemap)
			return;

		global $config;
      $start = strlen($config['base_url'])+1;
      $index = 0;
      foreach($pages as &$page)
      {
          $key = substr ($page['url'], $start);
          
          if($key == '')
             $key = $index++;
          
          if(substr($key, strlen($key)-1) == DIRECTORY_SEPARATOR)
             $key = substr($key, 0, strlen($key)-1);
          
		    $p[$key] = $page['title'];
      } 
      
		ksort ( $p , SORT_STRING);
		
      $sitemap = "<ul>";
		foreach($p as $url => $title)
			$sitemap .= "<li class=\"level".count(explode( "/", $url))."\"> <a href=\"$url\">$title</a></li>\n";
		
		$this->content .= $sitemap . "</ul>";
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