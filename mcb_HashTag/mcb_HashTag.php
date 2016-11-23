<?php
/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_HashTag
 * @version 0.5
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 * 	+ 2015-11-07 Upgrade to AbstractPicoPlugin for Pico 1.0
 *    + 2016-11-17 Added support for twitter user names
 *    + 2016-11-19 Better support to almost match twitter specification
 *    + 2016-11-22 MacPort version number was shown as a user account
 */
class mcb_HashTag extends AbstractPicoPlugin {

	private $searchUrl   = "https://twitter.com/search?q=";
   private $profileUrl  = "https://twitter.com/intent/user?screen_name=";
	private $remove_hash = false;
	private $lang        = "de";

   public function onConfigLoaded(&$settings)
	{
		if(isset($settings['mcb_hashtag_se']))
			$this->searchUrl = $settings['mcb_hashtag_se'];

		if(isset($settings['mcb_hashtag_lang']))
			$this->lang = $settings['mcb_hashtag_lang'];

		if(isset($settings['mcb_hashtag_remove_hash']))
			$this->remove_hash = $settings['mcb_hashtag_remove_hash'];
	}

   public function onContentParsed(&$content)
	{
		$fmt   = $this->remove_hash ? "\$1" : "#\$1";
		$href  = $this->searchUrl ? " href=\"$this->searchUrl%23$1\"" : "";
		$title = " title=\"Twitter Hashtag\"";

	   // thanks to minaz (http://stackoverflow.com/a/35498078)
	   //
	   // \p{Pc} - to match underscore
      // \p{N} - numeric character in any script
      // \p{L} - letter from any language
      // \p{Mn} - any non marking space (accents, umlauts, etc)
      //
      // not working; does not know why at the moment
      // currently solved with: äöüÄÖÜáàéèìíóòúùâêîôû in regex
      // but not complete

      // thanks to Wiktor Stribiżew for http://stackoverflow.com/a/30121395
		$content = preg_replace ( '/(?s)<pre[^<]*>.*?<\\/pre>(*SKIP)(*F)|(?!<![\'\-"0-9])\s+#+([[:alnum:]_äöüÄÖÜáàéèìíóòúùâêîôû]{1,139})(?![\w\-\.\'\!\?"])/m'
								, "<a$href$title class=\"hashtag\">$fmt</a>"
								, $content);


      $href = " href=\"$this->profileUrl\$1&lang=$this->lang\"";
      $title = " title=\"Twitter Benutzer\"";

		$content = preg_replace ( '/(?s)<pre[^<]*>.*?<\\/pre>(*SKIP)(*F)|(?!<![\'"])\s+@+([[:alnum:]_äöüÄÖÜáàéèìíóòúùâêîôû]*)(?![\w\'"\.])/i'
								, "<a$href$title class=\"twitteraccount\">@\$1</a>"
								, $content);
	}
	/* debug
   public function onPageRendered(&$output)
	{
		$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
	}*/
}
?>