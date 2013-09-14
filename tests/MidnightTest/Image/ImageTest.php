<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace MidnightTest\Image;

use Midnight\Image\Exception\InvalidPluginManagerException;
use Midnight\Image\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testCanOpenImage()
    {
        $image = Image::open('data/php.jpg');
        $this->assertInstanceOf('\Midnight\Image\Image', $image);
    }

    /**
     * @expectedException \Midnight\Image\Exception\FileDoesNotExistException
     */
    public function testExceptionIsThrownIfImageDoesNotExist()
    {
        Image::open('this/file/does/not/exist.jpg');
    }

    public function testCanGetFile()
    {
        $file = 'data/php.jpg';
        $image = Image::open($file);
        $this->assertEquals($file, $image->getFile());
    }

    public function testCanCallFilter()
    {
        $image = Image::open('data/php.jpg')->fit(array('width' => 100, 'height' => 100));
        $this->assertInstanceOf('Midnight\Image\Image', $image);
    }

    public function testCanSetPluginManagerByClassName()
    {
        $image = Image::open('data/php.jpg');
        $image->setHelperPluginManager('Midnight\Image\ImagePluginManager');
        $this->assertInstanceOf('Midnight\Image\ImagePluginManager', $image->getHelperPluginManager());
    }

    /**
     * @expectedException \Midnight\Image\Exception\InvalidPluginManagerException
     */
    public function testExceptionIsThrownIfPluginManagerClassDoesNotExsist()
    {
        $image = Image::open('data/php.jpg');
        $image->setHelperPluginManager('Class\Does\Not\Exist');
    }

    /**
     * @expectedException \Midnight\Image\Exception\InvalidPluginManagerException
     */
    public function testExceptionIsThrownIfPluginManagerIsNotAnInstanceOfImagePluginManager()
    {
        $image = Image::open('data/php.jpg');
        $image->setHelperPluginManager(new \stdClass());
    }

    public function testDefaultPluginManagerIsAlwaysTheSameObject()
    {
        $image1 = Image::open('data/php.jpg');
        $image2 = Image::open('data/php.jpg');
        $this->assertTrue($image1->getHelperPluginManager() === $image2->getHelperPluginManager());
    }
}
