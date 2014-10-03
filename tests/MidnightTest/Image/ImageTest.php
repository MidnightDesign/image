<?php

namespace MidnightImage\Image;

use Midnight\Image\Image;
use PHPUnit_Framework_TestCase;

class ImageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private static $assetsPath;

    public static function setUpBeforeClass()
    {
        self::$assetsPath = realpath(__DIR__ . '/../../assets');
    }

    public function testOpenJpeg()
    {
        $imagePath = self::$assetsPath . '/test.jpg';
        $image = Image::open($imagePath);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($imagePath, $image->getFile());
    }

    /**
     * @expectedException \Midnight\Image\Exception\FileNotFoundException
     */
    public function testOpenThrowsIfFileDoesNotExist()
    {
        Image::open('somefilethatdoesnotexist.jpg');
    }

    public function testContain()
    {
        $image = $this->getTestJpeg();
        $width = 200;
        $height = 1000;
        $resized = $image->contain($width, $height);
        $size = getimagesize($resized->getFile());
        $this->assertLessThanOrEqual($width, $size[0]);
        $this->assertLessThanOrEqual($height, $size[1]);
    }

    public function testCover()
    {
        $image = $this->getTestJpeg();
        $width = 200;
        $height = 100;
        $resized = $image->cover($width, $height);
        $size = getimagesize($resized->getFile());
        $this->assertGreaterThanOrEqual($width, $size[0]);
        $this->assertGreaterThanOrEqual($height, $size[1]);
    }

    /**
     * @return Image
     */
    private function getTestJpeg()
    {
        return Image::open(self::$assetsPath . '/test.jpg');
    }
}
