<?php

namespace Midnight\Image;

use Midnight\Image\ImageFeature\FileNameAware;
use Midnight\Image\ImageFeature\HeightAware;
use Midnight\Image\ImageFeature\ResourceAware;
use Midnight\Image\ImageFeature\WidthAware;

class ResourceImage implements ImageInterface, ResourceAware, WidthAware, HeightAware, FileNameAware
{
    /**
     * @var resource
     */
    private $resource;
    /**
     * @var string|null
     */
    private $fileName;

    /**
     * @param resource $resource
     * @param string   $fileName
     */
    public function __construct($resource, $fileName = null)
    {
        $this->resource = $resource;
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        $path = sys_get_temp_dir() . '/' . $this->getFileName();
        imagejpeg($this->resource, $path, 85);
        return $path;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return imagesx($this->resource);
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return imagesy($this->resource);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        $fileName = $this->fileName;
        if (!$fileName) {
            ob_start();
            imagepng($this->resource);
            $contents = ob_get_contents();
            ob_end_clean();
            $fileName = md5($contents);
        }
        return $fileName;
    }
}
