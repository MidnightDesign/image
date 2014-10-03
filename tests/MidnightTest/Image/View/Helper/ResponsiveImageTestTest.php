<?php

namespace MidnightTest\Image\View\Helper;

use Midnight\Image\View\Helper\ResponsiveImage;
use Midnight\Image\View\Helper\ResponsiveImageFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResponsiveImageTestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new ResponsiveImageFactory();
        $service = $factory->createService($this->getMock(ServiceLocatorInterface::class));
        $this->assertInstanceOf(ResponsiveImage::class, $service);
    }
}
