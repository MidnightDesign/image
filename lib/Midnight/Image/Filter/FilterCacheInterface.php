<?php

namespace Midnight\Image\Filter;

interface FilterCacheInterface
{
    /**
     * @param AbstractImageFilter $filter
     * @return bool
     */
    public function exists(AbstractImageFilter $filter);

    /**
     * @param AbstractImageFilter $filter
     * @return string
     */
    public function getPath(AbstractImageFilter $filter);
}