Plugin Image Resizer for Joomla 3.x
===================================

Allows You to generate thumbnails in a very simple way

REQUIREMENTS
------------

The minimum requirement by this plugin that your Web server supports PHP > 5.4.0. and Joomla 3.x


USAGE
------------

Install plugin

```php

$plugin = JPluginHelper::getPlugin('system', 'imageresizer');
if(!empty($plugin)) {
	// plugin installed and enabled
	JPluginHelper::importPlugin('system');
	$dispatcher = JEventDispatcher::getInstance();
} else {
	// plugin not installed or not enabled
	$dispatcher = NULL;
}


$image = 'images/joomla_black.png';
$width = 250; // thumb width in px
$height = 187; // thumb height in px

if(!empty($dispatcher) {
	$thumb = reset($dispatcher->trigger('resizeImage', array('image' => $image, 'width' => $width, 'height' => $height)));
} else {
	$thumb = $image;
}

```
