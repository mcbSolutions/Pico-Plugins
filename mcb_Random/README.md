Random
=============================================================================

Released under the [MIT license](http://opensource.org/licenses/MIT). Copyright (c) 2013 mcbSolutions.at

**Version** 0.0 alpha; Please report errors.

**Add's random text and/or image to your site**

Installation
=============================================================================
Copy/save the plugin into `plugins` folder

index.html
-----------------------------------------------------------------------------
Use Twig value `{{ random.txt1 }}`, `{{ random.txt2 }}`, `{{ random.txt1 }}` wherever you want

### Example 1

	{% if is_front_page %}
		<p><cite>{{ random.txt1 }}</cite><br/>
			<sup class="floatRight">{{ random.txt2 }}</sup>
			{{ random.img }}
		</p>
	{% endif %}  
	
### Example 2

	{% if is_front_page %}
		<p>{{ random.txt1 }}{{ random.img }}</p>
	{% else %} 
		{{ random.txt2 }}
	{% endif %}  
	

config
-----------------------------------------------------------------------------
### mcb_random_img_path
**string**  
Full path where the used images are located.

	$config['mcb_random_img_path'] = $config['base_url'] . "/content/images/";
	
### mcb_random_img_alt
**string**  
Title and alternative text for images, when missing in configuration
 
		$config['mcb_random_img_alt'] = "Screen";
### mcb_random
**string**  
Semikolon separated values for random  
	
		$config['mcb_random'][] = 'Text 1;Text 2;Image;Image title';
		
**Note:** You are able to add as many `$config['mcb_random'][] = ...` lines you like.
