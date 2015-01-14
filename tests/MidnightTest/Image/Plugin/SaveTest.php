<?php

namespace MidnightTest\Image\Plugin;

use Midnight\Image\Image;
use Midnight\Image\Plugin\Save;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $save = new Save('tests/assets/copy.jpg');
        $image = Image::open('tests/assets/test.jpg');
        $copy = $save($image);
        $this->assertFileExists($copy->getFile());
        unlink('tests/assets/copy.jpg');
    }

    public function testSaveWithDirectory()
    {
        $save = new Save('tests/assets');
        $save->setDestination('tests/assets/tmp');
        $image = Image::open('tests/assets/test.jpg');
        $copy = $save($image);
        $this->assertFileExists($copy->getFile());
        unlink($copy->getFile());
    }
}
