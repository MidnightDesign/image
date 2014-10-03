<?php

namespace MidnightTest\Image\Filter;

use MidnightTest\Image\Filter\Assets\BrokenFilter;

class AbstractFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testMissingFilterMethodThrowsException()
    {
        require_once __DIR__ . '/Assets/BrokenFilter.php';
        $broken = new BrokenFilter();
        $broken();
    }
}
