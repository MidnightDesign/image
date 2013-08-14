midnight/image
==============

A small image editing library for PHP

```php
$image = Image::open('path/to/image/.jpg');
$image = $image->fit(array('width' => 200, 'height' => 100));
echo $image->getFile();
```
