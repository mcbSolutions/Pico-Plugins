<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_TableOfContent
 * @version 0.3
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 *
 * ## Changelog
 *
 * 	+ 2016-11-17 Upgrade to AbstractPicoPlugin for Pico 1.0
 *    + 2016-11-19
 *       + Removed some parameters
 *       + Added the test page
 */
class mcb_TableOfContent {

   // default settings
   private $depth       = 3;
   private $min_headers = 3;
   private $top_txt     = 'Top';
   private $caption     = "Table of contents";


   // internal
   private $toc = '';
   private $xpQuery;
	private $content;

   private function makeToc(&$content)
   {
      //get the headings
      if(preg_match_all('/<h[1-'.$this->depth.']{1,1}[^>]*>.*?<\/h[1-'.$this->depth.']>/s',$content,$headers) === false)
         return "";

      //create the toc
      $this->headers = $headers;
      $heads = implode("\n",$headers[0]);

      $heads = preg_replace('/<a.+?\/a>/','',$heads);
      $heads = preg_replace('/<h([1-6]).+?id="?/','<li class="toc$1"><a href="#',$heads);
      $heads = preg_replace('/<\/h[1-6]>/','</a></li>',$heads);

      $cap = $this->caption =='' ? "" :  '<p id="toc-header">'.$this->caption.'</p>';

      return '<div id="toc">'.$cap.'<ul>'.$heads.'</ul></div>';
   }

   public function config_loaded(&$settings)
   {
      if(isset($settings['mcb_toc_depth'      ])) $this->depth       = &$settings['mcb_toc_depth'];
      if(isset($settings['mcb_toc_min_headers'])) $this->min_headers = &$settings['mcb_toc_min_headers'];
      if(isset($settings['mcb_toc_top_txt'    ])) $this->top_txt     = &$settings['mcb_toc_top_txt'];
      if(isset($settings['mcb_toc_caption'    ])) $this->caption     = &$settings['mcb_toc_caption'];

      for ($i=1; $i <= $this->depth; $i++) {
         $this->xpQuery[] = "//h$i";
      }
      $this->xpQuery = join("|", $this->xpQuery);
   }

   public function after_parse_content(&$content)
   {
      if(trim($content)=="")
        return;


      // enable user error handling
      // libxml_use_internal_errors(true);

      // Workaround from cbuckley:
      // "... an alternative is to prepend the HTML with an XML encoding declaration, provided that the
      // document doesn't already contain one:
      //
      // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
      $dom = new DOMDocument('1.0', 'utf-8');
      $html = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
      if(!$html)
		{
			foreach (libxml_get_errors() as $error) {
		        // handle errors here
		        $this->content['err'][] = $error;
		    }

		    libxml_clear_errors();

		    return;
		}
      $xp = new DOMXPath($dom);

      $nodes =$xp->query($this->xpQuery);

      if($nodes->length < $this->min_headers)
         return;

      // add missing id's to the h tags
      $id = 0;

      foreach($nodes as $i => $sort)
      {
          if (isset($sort->tagName) && $sort->tagName !== '')
          {
             if($sort->getAttribute('id') === "")
             {
                ++$id;
                $sort->setAttribute('id', "mcb_toc_head$id");

                $a = $dom->createElement('a', $this->top_txt);
                $a->setAttribute('href', '#top');
                $a->setAttribute('class', 'toc-nav');
                $a->setAttribute("mcb_toc_nav$id", 'toc-nav');
                $sort->appendChild($a);
             }
          }
      }
      // add top anchor
      $body = $xp->query("//body/node()")->item(0);
      $a = $dom->createElement('a');
      $a->setAttribute('name', 'top');
      $body->parentNode->insertBefore($a, $body);

      $content = preg_replace(array("/<(!DOCTYPE|\?xml).+?>/", "/<\/?(html|body)>/"),
                              array(                       "",                   ""),
                              $dom->saveHTML());

      $this->toc = $this->makeToc($content);
   }

   public function before_render(&$twig_vars, &$twig)
   {
      $twig_vars['mcb_toc'    ] = $this->toc;
      $twig_vars['mcb_toc_top'] = '<a name="top"></a>';
   }

   /* debug
   public function after_render(&$output)
   {
      $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
   }*/
}
