<?php

namespace Midnight\Image\Plugin;

use Midnight\Image\ImageInterface;

class ImageUrl implements PluginInterface
{
    /**
     * @var string
     */
    private $publicDirectory;

    /**
     * @param string $publicDirectory
     */
    public function __construct($publicDirectory)
    {
        $this->publicDirectory = $publicDirectory;
    }

    public function __invoke(ImageInterface $image)
    {
        return str_replace(realpath($this->publicDirectory), '', realpath($image->getFile()));
    }
}
