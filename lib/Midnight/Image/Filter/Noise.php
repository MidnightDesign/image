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

class Noise extends AbstractGdFilter
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
        parent::filter($value);

        $cache = $this->getCache();
        if ($cache->exists($this)) {
            return Image::open($cache->getPath($this));
        }

        $gd = $this->getGdImage();

        imagealphablending($gd, true);

        // Generate random colors
        $colors = array();
        for ($i = 0; $i < 1000; $i++) {
            $rand = preg_replace('/\D/', '', sha1(rand(0, 99999999) . uniqid() . microtime(true)));

            $red = round(substr($rand, 0, 3) / 4);
            $green = round(substr($rand, 3, 3) / 4);
            $blue = round(substr($rand, 6, 3) / 4);
            $alpha = round(127 - substr($rand, 9, 3) / 64);

            $colors[] = imagecolorallocatealpha($gd, $red, $green, $blue, $alpha);
        }

        $num_colors = sizeof($colors);
        for ($y = 0; $y < imagesy($gd); $y++) {
            for ($x = 0; $x < imagesx($gd); $x++) {
                $rand = mt_rand(0, $num_colors - 1);
                imagesetpixel($gd, $x, $y, $colors[$rand]);
            }
        }

        $image = $this->save($gd);
        imagedestroy($gd);
        return $image;
    }
}