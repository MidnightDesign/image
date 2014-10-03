<?php

namespace Midnight\Image\Util;

use Midnight\Image\ImageFeature\ResourceAware;
use Midnight\Image\ImageInterface;

class ResourceUtils
{
    /**
     * @param ImageInterface $image
     *
     * @return resource
     */
    public static function imageToResource(ImageInterface $image)
    {
        return $image instanceof ResourceAware
            ? $image->getResource()
            : imagecreatefromstring(file_get_contents($image->getFile()));
    }
} 
