<?php

/**
 * @see README.mb for further details
 *
 * @package Pico
 * @subpackage mcb_Blog
 * @version 0.0 alpha
 * @author mcbSolutions.at <dev@mcbsolutions.at>
 */
class mcb_Blog {

    private $mcb_is_blog_start;
    private $mcb_is_blog_page;
    private $data;

    private function checkBlog($url, &$isStart, &$isPage)
    {
       $isStart = stristr($url, "blog") !== false;
       $isPage  = $isStart & stristr($url, "/") !== false;
       $isStart = $isStart & !$isPage;
    }

    public function request_url(&$url)
    {
       $this->checkBlog($url, $this->mcb_is_blog_start, $this->mcb_is_blog_page);
    }

    public function get_page_data(&$data, $page_meta)
    {
       $this->checkBlog($data['url'], $data['mcb_is_blog_start'], $data['mcb_is_blog_page']);
    }

    public function before_render(&$twig_vars, &$twig)
    {
      $twig_vars['mcb_is_blog_start'] = $this->mcb_is_blog_start;
      $twig_vars['mcb_is_blog_page' ] = $this->mcb_is_blog_page;
    }
    /* debug
    public function after_render(&$output)
    {
        $output = $output . "<pre style=\"background-color:white;\">".htmlentities(print_r($this,1))."</pre>";
    }*/
}
