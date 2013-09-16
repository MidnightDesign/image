<?php

namespace MidnightTest\Image\Filter;

use Midnight\Image\Image;

class FitTest extends \PHPUnit_Framework_TestCase
{
    public function testCanResizePng()
    {
        $image = Image::open('data/php.png')->fit(array('width' => 100, 'height' => 100));
        $this->assertInstanceOf('Midnight\Image\Image', $image);
    }

    public function testCanResizeGif()
    {
        $image = Image::open('data/php.gif')->fit(array('width' => 100, 'height' => 100));
        $this->assertInstanceOf('Midnight\Image\Image', $image);
    }

    /**
     * @expectedException \Midnight\Image\Exception\UnknownImageTypeException
     */
    public function testExceptionIsThrownIfImageTypeIsUnknown()
    {
        $image = Image::open('data/invalid.txt')->fit(array('width' => 100, 'height' => 100));
        $this->assertInstanceOf('Midnight\Image\Image', $image);
    }
}
