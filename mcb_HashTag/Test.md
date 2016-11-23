---
Title: 				mcb_Hashtag
Subtitle: 		Testpage for plugin mcb_Hashtag
Description: 	Testpage for plugin
Keywords: 		Test, Plugin
Template:		
---

Useful for validating a text input (an HTML form in your CMS or custom application) that must be a valid Twitter hashtag.

## Valid examples: 

### Markdown
~~~~
+ Beginning with ascii char: #i, #lorem, #ipsum
+ Beginning with underscore: #_, #_i, #_lorem, #_ipsum
+ Beginning with underscore and number: #_1, #_1lorem, #_1ipsum
+ Beginning with number: #1, #1lorem, #1ipsum
+ Letters from any language: #Lürüm #üpsöm, #asáéìôü, #123hàsh_täg446
+ With underscore: #lorem_ipsum
+ Up to 140 characters including '#': #LoremipsumadquiametdolorevitaeceteroquaerendummeleaFacilisfastidiiduonoVirispartiendoiusnoaliaanimalnamatFeugaitimperdietiusannoquisfacerlu
+ Twitter name: @mcbSolutions, @Jürgen
~~~~

## Rendered

+ Beginning with ascii char: #i, #lorem, #ipsum
+ Beginning with underscore: #_, #_i, #_lorem, #_ipsum
+ Beginning with underscore and number: #_1, #_1lorem, #_1ipsum
+ Beginning with number: #1, #1lorem, #1ipsum
+ Letters from any language: #Lürüm #üpsöm, #_á_à_é_è_í_ì_ô_ü, #123hàsh_täg446
+ With underscore: #lorem_ipsum
+ Up to 140 characters including '#': #LoremipsumadquiametdolorevitaeceteroquaerendummeleaFacilisfastidiiduonoVirispartiendoiusnoaliaanimalnamatFeugaitimperdietiusannoquisfacerlu
+ Twitter name: @mcbSolutions, @Jürgen




## Invalid examples:
### Markdown

~~~~
+ Two hashes: ##hashtag
+ With minus: #hash-tag
+ With point: #hash.tag
+ Special characters: #hashtag!, #hashtag?
+ Quoted: '#i', "#i"
+ Already linked: <a href="https://mcbsolutions.at">#linked</a>
+ #abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789 - more than 140 characters
+ dev@mcbSolutions.at - Email
+ automake @1.14.1_2 - Version number
~~~~

### Rendered

**Note:** Nothing should be tagged.

+ Two hashes: ##hashtag
+ With minus: #hash-tag
+ With point: #hash.tag
+ Special characters: #hashtag!, #hashtag?
+ Quoted: '#i', "#i"
+ Already linked: <a href="https://mcbsolutions.at">#linked</a>
+ Lorem #abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789abcdefghij0123456789 - more than 140 characters
+ dev@mcbSolutions.at - Email
+ automake @1.14.1_2 - Version number


## Test for ...
No linked hashtags should be here

### ... &lt;pre&gt; tag

~~~~
#!/bin/bash 
Lorem #ipsum ad qui amet dolore, vitae cetero quaerendum mel ea. 
#comment in script
# Facilis fastidii duo no. 
~~~~

### ... Console
<pre class="console osx"><code>
#!/bin/bash 
Lorem #ipsum ad qui amet dolore, vitae cetero quaerendum mel ea. 
#comment in script
# Facilis fastidii duo no. 
</code></pre>

<pre class="console win"><code>
#!/bin/bash 
Lorem #ipsum ad qui amet dolore, vitae cetero quaerendum mel ea. 
#comment in script
# Facilis fastidii duo no. 
</code></pre>
