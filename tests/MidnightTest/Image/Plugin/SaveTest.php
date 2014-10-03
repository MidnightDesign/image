<?php

namespace MidnightTest\Image\Plugin;

use Midnight\Image\Image;
use Midnight\Image\Plugin\Save;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $save = new Save('assets/copy.jpg');
        $image = Image::open('assets/test.jpg');
        $copy = $save($image);
        $this->assertFileExists($copy->getFile());
        unlink('assets/copy.jpg');
    }

    public function testSaveWithDirectory()
    {
        $save = new Save('assets');
        $save->setDestination('assets/tmp');
        $image = Image::open('assets/test.jpg');
        $copy = $save($image);
        $this->assertFileExists($copy->getFile());
        unlink($copy->getFile());
    }
}
