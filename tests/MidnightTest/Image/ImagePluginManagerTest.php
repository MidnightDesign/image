<?php

namespace MidnightTest\Image;

use Midnight\Image\ImagePluginManager;

class ImagePluginManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Midnight\Image\Exception\InvalidPluginException
     */
    public function testExceptionIsThrownIfPluginIsInvalid()
    {
        $manager = new ImagePluginManager();
        $manager->validatePlugin(new \stdClass());
    }
}
