<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Image;
use Midnight\Image\Info\Type;

class Desaturate extends AbstractGdFilter
{
    /**
     * @var array
     */
    protected $options = array('amount' => null);

    /**
     * @param  Image $value
     * @throws Exception
     * @return Image
     */
    public function filter($value)
    {
        $amount = $this->options['amount'];
        if (empty($amount)) {
            throw new Exception('There is no amount set.');
        }

        parent::filter($value);

        $cache = $this->getCache();
        if ($cache->exists($this)) {
            return Image::open($cache->getPath($this));
        }

        $gd = $this->getGdImage();
        imagefilter($gd, IMG_FILTER_CONTRAST, $amount);
        $image = $this->save($gd);
        imagedestroy($gd);
        return $image;
    }
}