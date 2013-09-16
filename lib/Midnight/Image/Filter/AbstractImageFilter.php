<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image\Filter;

use Midnight\Image\Image;
use Midnight\Image\ImageInterface;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

abstract class AbstractImageFilter extends AbstractFilter
{

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * @var FilterCacheInterface
     */
    private $cache;

    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     */
    protected function setImage(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * @param  ImageInterface $image
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return ImageInterface
     */
    public function filter($image)
    {
        $this->setImage($image);
    }

    /**
     * @return FilterCacheInterface
     */
    protected function getCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new FilterCache();
        }
        return $this->cache;
    }

}