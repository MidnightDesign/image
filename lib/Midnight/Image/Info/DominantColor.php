<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image\Info;

use Midnight\Color\Color;
use Midnight\Image\Image;

class DominantColor
{
    /**
     * @var Image
     */
    private $image;
    /**
     * @var resource
     */
    private $gdImage;
    /**
     * @var array
     */
    private $defaults = array(
        'min_saturation' => null,
        'max_saturation' => null,
        'min_lightness' => null,
        'max_lightness' => null,
    );

    public function __invoke($image)
    {
        return $this->info($image);
    }

    /**
     * @param Image $image
     * @param array $options
     * @return Color|null
     */
    public function info(Image $image, array $options = array())
    {
        $this->image = $image;

        $options = $options + $this->defaults;

        $gd = $this->getGdImage();

        $width = imagesx($gd);
        $height = imagesy($gd);

        $colors = array();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($gd, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $r = round($r / 25.5) * 25.5;
                $g = round($g / 25.5) * 25.5;
                $b = round($b / 25.5) * 25.5;

                $hash = $r . '-' . $g . '-' . $b;
                if (!isset($colors[$hash])) {
                    $colors[$hash] = 0;
                }
                $colors[$hash]++;
            }
        }
        asort($colors);
        $keys = array_keys($colors);
        $first_color = null;

        do {
            $usable = true;

            $last_color = array_pop($keys);
            if (!$last_color) {
                $color = $first_color;
                break;
            }
            list($red, $green, $blue) = explode('-', $last_color);
            $color = new Color($red, $green, $blue);

            if ($first_color === null) {
                $first_color = $color;
            }

            if ($options['min_saturation'] !== null && $this->getSaturation($color) <= $options['min_saturation']) {
                $usable = false;
            }
            if ($options['max_saturation'] !== null && $this->getSaturation($color) >= $options['max_saturation']) {
                $usable = false;
            }
            if ($options['min_lightness'] !== null && $this->getLightness($color) <= $options['min_lightness']) {
                $usable = false;
            }
            if ($options['max_lightness'] !== null && $this->getLightness($color) >= $options['max_lightness']) {
                $usable = false;
            }
        } while (!$usable);

        return $color;
    }

    protected function getGdImage()
    {
        if (!$this->gdImage) {
            $type = $this->getImageType();
            $filename = $this->image->getFile();
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

    /**
     * @return string
     */
    protected function getImageType()
    {
        $typeInfo = new Type();
        return $typeInfo->getType($this->image);
    }

    private function getSaturation(Color $color)
    {
        $hsl = $this->getHsl($color);
        return $hsl['s'];
    }

    public function getHsl(Color $color)
    {
        $red = $color->getR() / 255;
        $green = $color->getG() / 255;
        $blue = $color->getB() / 255;
        $max = max(array($red, $green, $blue));
        $min = min(array($red, $green, $blue));
        $h = $s = $l = (($min + $max) / 2);
        if ($max === $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > .5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            switch ($max) {
                case $red:
                    $h = ($green - $blue) / $d + ($green < $blue ? 6 : 0);
                    break;
                case $green:
                    $h = ($blue - $red) / $d + 2;
                    break;
                case $blue:
                    $h = ($red - $green) / $d + 4;
                    break;
            }
            $h /= 6;
            $h = round($h * 360);
        }
        return array('h' => $h, 's' => $s, 'l' => $l);
    }

    public function getLightness(Color $color)
    {
        $hsl = $this->getHsl($color);
        return $hsl['l'];
    }
}