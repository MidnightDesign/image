<?php

namespace Midnight\Image\View\Helper;

use DOMDocument;
use Midnight\Image\ImageFeature\HeightAware;
use Midnight\Image\ImageFeature\WidthAware;
use Midnight\Image\ImageInterface;
use Midnight\Image\Plugin\ImageUrl;
use Midnight\Image\Plugin\PluginInterface;
use Midnight\Image\Plugin\Save;

class ResponsiveImage implements PluginInterface
{
    /**
     * @var string
     */
    private $destination;
    /**
     * @var string
     */
    private $publicDirectory;

    /**
     * @param string $destination
     * @param string $publicDirectory
     */
    public function __construct($destination, $publicDirectory)
    {
        $this->destination = $destination;
        $this->publicDirectory = $publicDirectory;
    }

    public function __invoke(ImageInterface $image)
    {
        $document = new DOMDocument();
        $img = $document->createElement('img');
        $document->appendChild($img);
        $img->setAttribute('src', $image->getFile());
        $images = $this->getImages($image);
        $srcsetParts = [];
        $imageUrl = new ImageUrl($this->publicDirectory);
        foreach ($images as $i) {
            // @codeCoverageIgnoreStart
            if (!$i instanceof WidthAware) {
                throw new \RuntimeException();
            }
            // @codeCoverageIgnoreEnd
            $srcsetParts[] = $imageUrl($i) . ' ' . $i->getWidth() . 'w';
        }
        $img->setAttribute('srcset', join(', ', $srcsetParts));
        return $document->saveHTML();
    }

    /**
     * @param ImageInterface $image
     * @return ImageInterface[]
     */
    private function getImages(ImageInterface $image)
    {
        // @codeCoverageIgnoreStart
        if (!$image instanceof WidthAware) {
            throw new \RuntimeException();
        }
        // @codeCoverageIgnoreEnd
        $originalImageWidth = $image->getWidth();
        $images = [];
        foreach ([1, 1 / 2, 1 / 4] as $width) {
            $resized = $image->contain($originalImageWidth * $width, 999999);
            $images[] = $this->saveImage($resized);
        }
        return $images;
    }

    private function saveImage(ImageInterface $image)
    {
        $save = new Save($this->destination);
        // @codeCoverageIgnoreStart
        if (!$image instanceof WidthAware || !$image instanceof HeightAware) {
            throw new \RuntimeException('Unable to create a file name.');
        }
        // @codeCoverageIgnoreEnd
        $fileNameParts = explode('.', basename($image->getFile()));
        $fileName = $this->destination . '/' . $fileNameParts[0] . '-' . $image->getWidth() . 'x' . $image->getHeight();
        array_shift($fileNameParts);
        if ($fileNameParts) {
            $fileName .= '.' . join('.', $fileNameParts);
        }
        $save->setDestination($fileName);
        return $save($image);
    }
} 
