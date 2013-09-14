midnight/image
==============
A small image editing library for PHP

Installation
------------
The library is available as a Composer package:

```
./composer.phar require midnight/image
```

Usage
-----
Open an image:
```php
$image = \Midnight\Image\Image::open('path/to/image.jpg');
```

Apply a filter:
```php
$image->fit(array('width' => 150, 'height' => 200));
```

Get the new image after applying a filter:

```php
$image->getFile();
```

Included filters
----------------
The library comes with some pre-defined filters. They can be accessed by calling the filter name as a method of the 
image object. All filters accept an options array as their only argument. For example:

```php
$options = array('foo' => true, 'bar' => 42);
$filtered_image = $image->filterName($options);
```

### `fit`
Scales the image up or down to fit it inside a rectangle defined by the options `width` and `height`. It's the equivalent of CSS's `background-size: contain` property or Adobe InDesign's "Fit proportionally" option.
```php
// $image has a size of 1500 by 2000 pixels
$thumbnail = $image->fit(array('width' => 200, 'height' => 200));
// $thumbnail is 150 x 200 pixels 
```
### `fill`
The `fill` filter fills a rectangle defined by the options `width` and `height`. It's the equivalent of CSS's `background-size: cover` property or Adobe InDesign's "Fill proportionally" option. It will most likely crop parts of the image.
```php
// $image has a size of 1500 by 2000 pixels
$thumbnail = $image->fill(array('width' => 200, 'height' => 200));
// $thumbnail is 200 x 200 pixels, with cropped areas at the top and bottom
```
### `brighten`
Brightens the image. The only option is `amount`, which accepts an integer from -255 (which actually darkens the image) to 255.
```php
$brightened = $image->brighten(array('amount' => 50));
```
### `desaturate`
Desaturates the image. The only option is `amount`, which accepts an integer from -100 (which actually saturates image) to 100.
```php
$desaturated = $image->desaturate(array('amount' => 25));
```
### `colorcast`
Applies a color cast to the image. The available options are `color`, which accepts an instance of `Midnight\Color\Color` (actually `ColorInterface`) and `amount`, which accepts a value between 0 and 1.
```php
// Apply a red color cast.
$red = new \Midnight\Color\Color(255, 0, 0);
$colorcasted = $image->colorcast(array('color' => $red, 'amount' => .2));
```
### `noise`
Adds noise to the image. The only option available is `amount`.
```php
$noisy = $image->noise(array('amount' => .1));
```
Adding a custom filter
----------------------
You can easily add a custom filter:

```php
class MyCustomFilter extends \Midnight\Image\Filter\AbstractGdFilter
{
    public function filter($image)
    {
        parent::filter($image);

        $cache = $this->getCache();
        if ($cache->exists($this)) {
            return Image::open($cache->getPath($this));
        }

        $im = $this->getGdImage();

        // Do stuff to GD image resource $im

        $image = $this->save($im);

        return $image;
    }
}
```
```php
$plugin_manager = \Midnight\Image\ImagePluginManager::getInstance();
$plugin_manager->setService('myCustomFilter', 'MyCustomFilter');

// Use the filter
$filtered = \Midnight\Image\Image::open('file.jpg')->myCustomFilter();
```
