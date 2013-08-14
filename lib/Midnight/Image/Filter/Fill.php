<?php

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Image;
use Midnight\Image\Info\Type;

class Fill extends AbstractGdFilter
{
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
        $target = imagecreatetruecolor($width, $height);

        $srcRatio = imagesx($source) / imagesy($source);
        $tgtRatio = imagesx($target) / imagesy($target);
        $ratio = $srcRatio / $tgtRatio;

        if ($ratio < 1) { // Use the width as the base
            $copyW = imagesx($source);
            $copyH = (imagesx($source) / imagesx($target)) * imagesy($target);
            $copyX = 0;
            $copyY = (imagesy($source) / 2) - ($copyH / 2);
        } else { // Use the height as the base
            $copyW = (imagesy($source) / imagesy($target)) * imagesx($target);
            $copyH = imagesy($source);
            $copyX = (imagesx($source) / 2) - ($copyW / 2);
            $copyY = 0;
        }

        imagecopyresampled($target, $source, 0, 0, $copyX, $copyY, $width, $height, $copyW, $copyH);

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