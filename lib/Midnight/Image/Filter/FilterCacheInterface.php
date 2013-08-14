<?php

namespace Midnight\Image\Filter;

interface FilterCacheInterface
{
    /**
     * @param AbstractImageFilter $filter
     * @return bool
     */
    public function exists(\Midnight\Image\Filter\AbstractImageFilter $filter);

    /**
     * @param AbstractImageFilter $filter
     * @return string
     */
    public function getPath(\Midnight\Image\Filter\AbstractImageFilter $filter);
}