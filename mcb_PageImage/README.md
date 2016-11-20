Page Image
=============================================================================

Released under the [MIT license](http://opensource.org/licenses/MIT). Copyright (c) 2013 mcbSolutions.at

**Version** 0.2; Please report errors.

**Image and thumbnail image for the pages array and the meta**

With this plugin you will get 4 new properties within the pages

+ `{{ page.img }}` ... full url of the image associated with the page
+ `{{ page.img_tag }}` ... full qualified image tag associated with the page
+ `{{ page.thmb }}` ... full url of the thumbnail image associated with the page
+ `{{ page.thmb_tag }}` ... full qualified image tag for the thumbnail associated with the page

Installation
=============================================================================
1. Copy/save the plugin into `plugins` folder
2. **OS X only:** Optionally install the [Create Homepage thumbnail](Create%20Homepage%20thumbnail.md) workflow for Automator in OS X to simply create the thumbnail images

index.html
-----------------------------------------------------------------------------
### Example for Page

    {{ current_page.img }}
    {{ current_page.img_tag }}
	{{ current_page.thmb }}
	{{ current_page.thmb_tag }}

### Example for listing pages

	{% for page in pages%}
      {{ page.img }}
      {{ page.img_tag }}
	  {{ page.thmb }}
	  {{ page.thmb_tag }}
    {% endfor %}

Optional: config
-----------------------------------------------------------------------------

### mcb_pageimage_ext

**string**
The extension of the images to assign. Default is `.png`.

	$config['mcb_pageimage_ext'] = ".jpg";


### mcb_pageimage_postfix

**string**
The postfix for the thimbnails. Default is `_th`.

	$config['mcb_pageimage_ext'] = "_thumbnail";


### mcb_pageimage_class
**string**
Class for the img tag. Default is none.

	$config['mcb_pageimage_class'] = "fancybox thumbnail";
