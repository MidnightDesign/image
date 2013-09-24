<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Exception\UnknownImageTypeException;
use Midnight\Image\Image;
use Midnight\Image\ImageInterface;
use Midnight\Image\Info\Type;

abstract class AbstractGdFilter extends AbstractImageFilter
{
    /**
     * @var string
     */
    public $originalMemoryLimit;

    protected function setImage(ImageInterface $image)
    {
        parent::setImage($image);
        $this->gdImage = null;
    }

    /**
     * @var resource
     */
    private $gdImage;

    protected function getGdImage()
    {
        if (!$this->gdImage) {
            $type = $this->getImageType();
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
                    throw new UnknownImageTypeException('Unrecognized image type ' . $type . '.');
                    break;
            }
        }
        return $this->gdImage;
    }

    /**
     * @return string
     */
    protected function getImageType()
    {
        $typeInfo = new Type();
        return $typeInfo->getType($this->getImage());
    }

    /**
     * @param resource $res
     * @return Image
     * @throws Exception
     */
    protected function save($res)
    {
        $cache_path = $this->getCache()->getPath($this);
        $image_type = $this->getImageType();
        switch ($image_type) {
            case Type::JPEG:
                imagejpeg($res, $cache_path, 100);
                break;
            case Type::GIF:
                imagegif($res, $cache_path);
                break;
            case Type::PNG:
                imagepng($res, $cache_path);
                break;
            default:
                throw new UnknownImageTypeException('Unrecognized image type ' . $image_type . '.');
                break;
        }
        if (!file_exists($cache_path)) {
            throw new Exception('Couldn\'t create cache image ' . $cache_path . '.');
        }
        chmod($cache_path, 0777);

        return Image::open($cache_path);
    }

    protected function increaseMemoryLimit()
    {
        $this->originalMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '-1');
        if (ini_get('memory_limit') !== '-1') {
            throw new Exception('Couldn\'t increase memory limit.');
        }
    }

    protected function resetMemoryLimit()
    {
        ini_set('memory_limit', $this->originalMemoryLimit);
    }

}