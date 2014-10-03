<?php

namespace Midnight\Image\Plugin;

use Midnight\Image\Image;
use Midnight\Image\ImageInterface;

class Save implements PluginInterface
{
    /**
     * @var string
     */
    private $destination;

    /**
     * @param string $destination
     */
    public function __construct($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @param ImageInterface $image
     * @return ImageInterface
     */
    public function __invoke(ImageInterface $image)
    {
        $imageFile = $image->getFile();
        $destinationPath = $this->destination;
        if (is_dir($this->destination)) {
            $destinationPath .= '/' . basename($imageFile);
        }
        copy($imageFile, $destinationPath);
        return Image::open($destinationPath);
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }
}
