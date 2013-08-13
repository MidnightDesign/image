<?php
/**
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT
 */

namespace Midnight\Image\Info;

use Midnight\Image\Image;
use Midnight\Image\ImageInterface;

class Type
{
    const JPEG = 'jpeg';
    const PNG = 'png';
    const GIF = 'gif';

    public function getType(ImageInterface $image)
    {
        $type = exif_imagetype($image->getFile());
        switch ($type) {
            case IMAGETYPE_JPEG:
                return self::JPEG;
                break;
            case IMAGETYPE_PNG:
                return self::PNG;
                break;
            case IMAGETYPE_GIF:
                return self::GIF;
                break;
            default:
                throw new \Exception('Unrecognized image type ' . $type . '.');
                break;
        }
    }
}