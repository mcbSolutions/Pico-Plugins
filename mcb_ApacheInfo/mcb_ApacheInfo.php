<?php
/**
 * @see README.md for further details
 *
 * @package Pico
 * @subpackage mcb_ApacheInfo
 * @version 0.0 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 * 	+ 2016-11-22 Created
 *
 */
final class mcb_ApacheInfo extends AbstractPicoPlugin
{
   private $is_me = false;
   private $e     = array();

   public function onRequestUrl(&$url)
   {
      $this->is_me = strpos(strtolower($url), 'apacheinfo') !== false;
   }

   public function onContentLoaded(&$rawContent)
   {
 	   if(!$this->is_me)
         return;

      //Get a list of loaded Apache modules
      $this->content = "## Modules:\n\n+ ";

      try {
         $this->content .= implode(@apache_get_modules(), "\n+ ");
      }
      catch(RuntimeException $e)
      {
         $this->content .= "Could not retrieve modules.\n\n";
         $this->e[] = $e;
      }
      $this->content .= "\n\n";

      //Fetch all HTTP request headers
      $this->content .= "## Request headers:\n\n ";
      try {
         foreach (@apache_request_headers() as $header => $value)
            $this->content .=  "\n+ **$header:** $value\n";
      }
      catch(RuntimeException $e)
      {
         $this->content .= "Could not retrieve request headers.\n\n";
         $this->e[] = $e;
      }
      $this->content .= "\n\n";

      //Fetch all HTTP response headers
      $this->content .= "## Response headers:\n\n ";
      try {
         foreach (@apache_response_headers() as $header => $value)
            $this->content .=  "\n+ **-$header:** -$value \n    `$header:$value`\n";

      }
      catch(RuntimeException $e)
      {
         $this->content .= "Could not retrieve response headers.\n\n";
         $this->e[] = $e;
      }
      $this->content .= "\n\n";
      $rawContent .= $this->content;
   }

   public function on404ContentLoaded(&$rawContent)
   {
 	   if(!$this->is_me)
         return;

		$rawContent = "";
   }

   public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
   {
      if(!$this->is_me)
         return;

      if(@$twigVariables['meta']['title']    == '') $twigVariables['meta']['title'] = "Apache Info";
      if(@$twigVariables['meta']['subtitle'] == '')
      {
         try {
            $version = "for " . @apache_get_version();
         }
         catch(RuntimeException $e)
         {
            $version = "Could not retrieve version " ;
            $this->e[] = $e;
         }

         $twigVariables['meta']['subtitle'] = $version;
      }
   }

   /* debug
   public function onPageRendered(&$output)
   {
		$output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}
