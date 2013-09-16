<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

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