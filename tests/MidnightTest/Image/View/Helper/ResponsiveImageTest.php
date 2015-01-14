<?php

namespace MidnightTest\Image\View\Helper;

use Midnight\Image\Image;
use Midnight\Image\View\Helper\ResponsiveImage;

class ResponsiveImageTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        $files = glob('tests/assets/tmp/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function testSimple()
    {
        $helper = new ResponsiveImage('tests/assets/tmp', 'assets');
        $image = Image::open('tests/assets/test.jpg');

        $markup = $helper($image);

        $this->assertRegExp('/tmp\/test-400x719.jpg/', $markup);
        $this->assertRegExp('/tmp\/test-200x359.jpg/', $markup);
        $this->assertRegExp('/tmp\/test-100x179.jpg/', $markup);
    }
}
