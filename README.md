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
JPluginHelper::importPlugin('system');
$dispatcher = JEventDispatcher::getInstance();

$image = 'images/joomla_black.png';
$width = 250; // thumb width in px
$height = 187; // thumb height in px

$thumb = reset($dispatcher->trigger('resizeImage', array('image' => $image, 'width' => $width, 'height' => $height)));
```
