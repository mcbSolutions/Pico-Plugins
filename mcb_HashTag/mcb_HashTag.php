<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_HashTag
 * @version 0.2
 * @author mcbSolutions.at <dev@mcbsolutions.at>

 */
class mcb_HashTag {

	private $se = "https://twitter.com/search?q=";
	private $remove_hash = false;

	public function config_loaded(&$settings)
	{
		if(isset($settings['mcb_hashtag_se']))
			$this->se = $settings['mcb_hashtag_se'];

		if(isset($settings['mcb_hashtag_remove_hash']))
			$this->remove_hash = $settings['mcb_hashtag_remove_hash'];
	}

	public function after_parse_content(&$content)
	{
		$fmt   = $this->remove_hash ? "\$1" : "#\$1";
		$href  = $this->se ? " href=\"$this->se%23$1\"" : "";
		$alt   = " alt=\"HashTag\"";
		$title = " title=\"HashTag\"";

		$content = preg_replace ( '/(?<![\'"])#([a-zA-Z_][a-zA-Z_0-9]*)(?![\'"])/s'
								, "<a$href$alt$title class=\"hashtag\">$fmt</a>"
								, $content);
	}
	/* debug
	public function after_render(&$output)
	{
		$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
	}*/
}
?>