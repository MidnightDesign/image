<?php

namespace Midnight\Image\Util;

use Midnight\Image\ImageFeature\HeightAware;
use Midnight\Image\ImageFeature\WidthAware;
use Midnight\Image\ImageInterface;

class SizeUtils
{
    /**
     * @param ImageInterface $image
     *
     * @return int
     */
    public static function getWidth(ImageInterface $image)
    {
        return $image instanceof WidthAware ? $image->getWidth() : getimagesize($image->getFile())[0];
    }

    /**
     * @param ImageInterface $image
     *
     * @return int
     */
    public static function getHeight(ImageInterface $image)
    {
        return $image instanceof HeightAware ? $image->getHeight() : getimagesize($image->getFile())[1];
    }
} 
