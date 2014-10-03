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
        $this->imageUrl = new ImageUrl('assets');
    }

    public function testInvoke()
    {
        $imageUrl = $this->imageUrl;
        $image = Image::open('assets/test.jpg');
        $this->assertEquals('/test.jpg', $imageUrl($image));
    }
}
