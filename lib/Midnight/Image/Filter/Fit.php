<?php

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Image;

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
     * TODO Encapsulate caching in a separate class
     */
    public function filter($value)
    {
        parent::filter($value);
        $cacheFile = $this->makeCachePath(__METHOD__, array($this->getWidth(), $this->getHeight()));
        if (!file_exists($cacheFile)) {
            $originalMemoryLimit = ini_get('memory_limit');
            ini_set('memory_limit', '1024M');

            // Prevent division by zero
            if (!$this->getHeight()) {
                $this->setHeight(999999);
            }
            if (!$this->getWidth()) {
                $this->setWidth(999999);
            }

            $source = $this->getGdImage();

            // Calculate dimensions
            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);

            if ($sourceWidth / $this->getWidth() < $sourceHeight / $this->getHeight()) {
                $ratio = $sourceHeight / $maxHeight;
            } else {
                $ratio = $sourceWidth / $this->getWidth();
            }
            $targetWidth = round($sourceWidth / $ratio);
            $targetHeight = round($sourceHeight / $ratio);

            // Create destination image
            $target = imagecreatetruecolor($targetWidth, $targetHeight);

            // Place source image
            imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

            imagejpeg($target, $cacheFile, 100);
            if (!file_exists($cacheFile)) {
                throw new Exception('Couldn\'t create cache image ' . $cacheFile . '.');
            }
            chmod($cacheFile, 0777);

            unset($target);
            unset($source);

            ini_set('memory_limit', $originalMemoryLimit);
        }
        return Image::open($cacheFile);
    }

    private function makeCachePath($methodName, array $options = array())
    {
        $dir = self::CACHE_PATH;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . sha1($this->getImage()->getFile() . $methodName . join(' ', $options)) . '.jpg';
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