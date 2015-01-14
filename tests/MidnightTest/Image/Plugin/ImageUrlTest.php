<?php

namespace MidnightTest\Image\Plugin;

use Midnight\Image\Image;
use Midnight\Image\Plugin\ImageUrl;

class ImageUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImageUrl
     */
    private $imageUrl;

    public function setUp()
    {
        $this->imageUrl = new ImageUrl('tests/assets');
    }

    public function testInvoke()
    {
        $imageUrl = $this->imageUrl;
        $image = Image::open('tests/assets/test.jpg');
        $this->assertEquals('/test.jpg', $imageUrl($image));
    }
}
