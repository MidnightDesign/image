<?php

namespace MidnightTest\Image;

use Midnight\Image\ResourceImage;

class ResourceImageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetResource()
    {
        $im = imagecreate(100, 100);
        $image = new ResourceImage($im);
        $this->assertSame($im, $image->getResource());
    }

    public function testRandomFileNameIsGeneratedWhenNoneGiven()
    {
        $im = imagecreatetruecolor(100, 100);
        $image = new ResourceImage($im);
        $path = $image->getFile();
        $this->assertInternalType('string', $path);
    }
}
