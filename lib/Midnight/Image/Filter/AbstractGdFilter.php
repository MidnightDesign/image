<?php

namespace Midnight\Image\Filter;

use Midnight\Image\Info\Type;

abstract class AbstractGdFilter extends AbstractImageFilter
{

    private $gdImage;

    protected function getGdImage()
    {
        if (!$this->gdImage) {
            $typeInfo = new Type();
            $type = $typeInfo->getType($this->getImage());
            $filename = $this->getImage()->getFile();
            switch ($type) {
                case Type::JPEG:
                    $this->gdImage = imagecreatefromjpeg($filename);
                    break;
                case Type::PNG:
                    $this->gdImage = imagecreatefrompng($filename);
                    break;
                case Type::GIF:
                    $this->gdImage = imagecreatefromgif($filename);
                    break;
                default:
                    throw new \Exception('Unrecognized image type ' . $type . '.');
                    break;
            }
        }
        return $this->gdImage;
    }

}