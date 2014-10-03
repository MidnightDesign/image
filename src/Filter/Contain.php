<?php

namespace Midnight\Image\Filter;

use Midnight\Image\ImageFeature\FileNameAware;
use Midnight\Image\ImageInterface;
use Midnight\Image\ResourceImage;
use Midnight\Image\Util\ResourceUtils;
use Midnight\Image\Util\SizeUtils;

class Contain extends AbstractFilter implements FilterInterface
{
    /**
     * @param ImageInterface $image
     * @param int            $width
     * @param int            $height
     * @return ImageInterface
     */
    public function filter(ImageInterface $image, $width, $height)
    {
        $srcWidth = SizeUtils::getWidth($image);
        $srcHeight = SizeUtils::getHeight($image);

        $scale = min($width / $srcWidth, $height / $srcHeight);

        $dstWidth = floor($srcWidth * $scale);
        $dstHeight = floor($srcHeight * $scale);

        $src = ResourceUtils::imageToResource($image);
        $dst = imagecreatetruecolor($dstWidth, $dstHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstWidth, $dstHeight, $srcWidth, $srcHeight);

        $fileName = $image instanceof FileNameAware ? $image->getFileName() : null;

        return new ResourceImage($dst, $fileName);
    }
}
