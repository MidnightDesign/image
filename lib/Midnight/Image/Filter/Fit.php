<?php

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Image;
use Midnight\Image\Info\Type;

class Fit extends AbstractGdFilter
{
    const CACHE_PATH = 'data/cache/midnight/image/';
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $height;

    /**
     * @param  Image $value
     * @throws Exception
     * @return Image
     */
    public function filter($value)
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if (empty($width) || empty($height)) {
            throw new Exception('There must be a width and a height set.');
        }

        parent::filter($value);

        $cache = $this->getCache();
        if ($cache->exists($this)) {
            return Image::open($cache->getPath($this));
        }

        $this->increaseMemoryLimit();

        $source = $this->getGdImage();

        // Calculate dimensions
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        if ($sourceWidth / $width < $sourceHeight / $height) {
            $ratio = $sourceHeight / $height;
        } else {
            $ratio = $sourceWidth / $width;
        }
        $targetWidth = round($sourceWidth / $ratio);
        $targetHeight = round($sourceHeight / $ratio);

        // Create destination image
        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($target, false);
        imagesavealpha($target, true);

        // Place source image
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

        $image = $this->save($target);

        unset($target);
        unset($source);

        $this->resetMemoryLimit();

        return $image;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
}