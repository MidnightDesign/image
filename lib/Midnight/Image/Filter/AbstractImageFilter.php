<?php

namespace Midnight\Image\Filter;

use Midnight\Image\Image;
use Midnight\Image\ImageInterface;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

abstract class AbstractImageFilter extends AbstractFilter
{

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param  ImageInterface $image
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return ImageInterface
     */
    public function filter($image)
    {
        $this->image = $image;
    }

}