<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image\Info;

use Midnight\Image\Exception\UnknownImageTypeException;
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
                throw new UnknownImageTypeException('Unrecognized image type ' . $type . '.');
                break;
        }
    }
}