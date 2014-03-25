Blog
=============================================================================

Released under the [MIT license](http://opensource.org/licenses/MIT). Copyright (c) 2013 mcbSolutions.at

**Version** 0.0 alpha; Please report errors.

**Better organize a blog**

+ Determines the index file within the content/blog folder: `{{ mcb_is_blog_start }}`
+ Determines if a page is a blog page (a file within the content/blog folder): `{{ mcb_is_blog_page }}`
+ Best use with [Pico Navigation](https://github.com/ahmet2106/pico-navigation) when excluding `blog` folder

Installation
======================================================================
1. Copy/save the plugin into `plugins` folder

Content
-----------------------------------------------------------------------------
1. Each page of the blog has to be located in `content/blog`
2. The blog file itself contains the timestamp within the meta data of the MarkDown file

        /*
        Date: 2013-10-28
        */

index.html
-----------------------------------------------------------------------------
### Example to exlude Blog from the menu

    <nav id="nav">
        <ul>
            {% for page in pages if not page.date and page.title != 'Blog' %}
                {% if page.title == meta.title %}
                   <li class="curr"><a href="{{ page.url }}">{{ page.title }}</a></li>
                {% else %}
                   <li><a href="{{ page.url }}">{{ page.title }}</a></li>
                {% endif %}
            {% endfor %}
        </ul>
    </nav>

### Example for listing the 5 latest posts at the blog's start page

	{% if mcb_is_blog_start %}
   		{% set num = 0 %}
   		{% for page in pages if num < 5 %}
      		{% if page.mcb_is_blog_page %}
      			{% set num = num + 1 %}
                  <h5><a href="{{ page.url }}" title="{{ page.title }}">{{ page.title }}</a></h5>
                  <small class="date">{{ page.date_formatted }}</small>
                  <p style="font-size: smaller">{{ page.excerpt }}</p>
      		{% endif %}
   		{% endfor %}
	{% endif %}

### Example for Difference between news and blog entry

	{% if current_page.date and current_page.mcb_is_blog_page %}
		// blog entry
	{% elseif current_page.date %}
		// newsentry
	{% else %}
		// normal page
	{% endif %}

### Example for filtering pages list
**Note:** Works for news and blog

    {% for page in pages if page.date and page.mcb_is_blog_page == mcb_is_blog_page %}
          <li><a href="{{ page.url }}">{{ page.title }}</a> <sub>{{ page.date_formatted }}</sub>
    {% endfor %}
