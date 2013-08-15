<?php

namespace Midnight\Image\Filter;

use Midnight\Image\ImageInterface;

class FilterCache implements FilterCacheInterface
{
    const CACHE_ROOT = 'data/cache/midnight/image/';

    /**
     * @param AbstractImageFilter $filter
     * @return bool
     */
    public function exists(AbstractImageFilter $filter)
    {
        return is_file($this->getPath($filter));
    }

    /**
     * @param AbstractImageFilter $filter
     * @return string
     */
    public function getPath(AbstractImageFilter $filter)
    {
        $dir = self::CACHE_ROOT;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $image = $filter->getImage();
        return $dir . sha1($image->getFile() . get_class($filter) . serialize($filter->getOptions())) . $this->getExtension($image);
    }

    private function getExtension(ImageInterface $image)
    {
        return strrchr($image->getFile(), '.');
    }
}