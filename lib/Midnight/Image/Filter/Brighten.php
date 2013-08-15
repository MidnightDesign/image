<?php

namespace Midnight\Image\Filter;

use Exception;
use Midnight\Image\Image;
use Midnight\Image\Info\Type;

class Brighten extends AbstractGdFilter
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
        imagefilter($gd, IMG_FILTER_BRIGHTNESS, $amount);
        $image = $this->save($gd);
        imagedestroy($gd);
        return $image;
    }
}