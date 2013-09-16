<?php
/**
 * @author    Rudolph Gottesheim <r.gottesheim@loot.at>
 * @link      http://github.com/MidnightDesign
 * @copyright Copyright (c) 2013 Rudolph Gottesheim
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Midnight\Image;

use Exception;
use Midnight\Image\Exception\FileDoesNotExistException;
use Midnight\Image\Exception\InvalidPluginManagerException;
use Midnight\Image\Filter\AbstractImageFilter;

/**
 * Class Image
 * @package Midnight\Image
 * @method \Midnight\Image\Image brighten() brighten(array $options) Brightens the image by $options['amount']
 * @method \Midnight\Image\Image colorcast() colorcast(array $options) Adds a color cast to the image. Options: Midnight\Color\ColorInterface $color, float $amount
 * @method \Midnight\Image\Image desaturate() desaturate(array $options) Desaturates the image by $options['amount']
 * @method \Midnight\Image\Image fill() fill(array $options) Resize image to fill a specified area (accepts 'width' and 'height' as options)
 * @method \Midnight\Image\Image fit() fit(array $options) Resize image to fit inside a rectangle (accepts 'width' and 'height' as options)
 * @method \Midnight\Image\Image noise() noise() Adds random (duh) noise to the image
 * @method string type() Get image type (JPEG, GIF, PNG, etc.)
 */
class Image implements ImageInterface
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var AbstractImageFilter[] Cache for the plugin call
     */
    private $__pluginCache = array();
    /**
     * Helper plugin manager
     *
     * @var ImagePluginManager
     */
    private $__helpers;

    /**
     * @param string $file
     * @throws FileDoesNotExistException
     */
    private function __construct($file)
    {
        if (!is_string($file)) {
            throw new \InvalidArgumentException('The first argument passed to the Image constructor must be a string.');
        }
        if (!file_exists($file)) {
            throw new FileDoesNotExistException('The file ' . $file . ' does not exist.');
        }
        $this->file = $file;
    }

    /**
     * @param string $file
     * @return Image
     */
    public static function open($file)
    {
        return new self($file);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Overloading: proxy to helpers
     *
     * Proxies to the attached plugin manager to retrieve, return, and potentially
     * execute helpers.
     *
     * * If the helper does not define __invoke, it will be returned
     * * If the helper does define __invoke, it will be called as a functor
     *
     * @param  string $method
     * @param  array $argv
     * @return mixed
     */
    public function __call($method, $argv)
    {
        if (!isset($this->__pluginCache[$method])) {
            $this->__pluginCache[$method] = $this->plugin($method);
        }
        if (is_callable($this->__pluginCache[$method])) {
            if (!empty($argv[0])) {
                $this->__pluginCache[$method]->setOptions($argv[0]);
            }
            return call_user_func($this->__pluginCache[$method], $this);
        }
        return $this->__pluginCache[$method];
    }

    /**
     * Get plugin instance
     *
     * @param  string $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return AbstractImageFilter
     */
    public function plugin($name, array $options = null)
    {
        return $this->getHelperPluginManager()->get($name, $options);
    }

    /**
     * Get helper plugin manager instance
     *
     * @return ImagePluginManager
     */
    public function getHelperPluginManager()
    {
        if (null === $this->__helpers) {
            $this->setHelperPluginManager(ImagePluginManager::getInstance());
        }
        return $this->__helpers;
    }

    /**
     * Set helper plugin manager instance
     *
     * @param  string|ImagePluginManager $helpers
     * @return Image
     * @throws Exception
     */
    public function setHelperPluginManager($helpers)
    {
        if (is_string($helpers)) {
            if (!class_exists($helpers)) {
                throw new InvalidPluginManagerException(sprintf(
                    'Invalid helper helpers class provided (%s)',
                    $helpers
                ));
            }
            $helpers = new $helpers();
        }
        if (!$helpers instanceof ImagePluginManager) {
            throw new InvalidPluginManagerException(sprintf(
                'Helper helpers must extend Midnight\Image\ImagePluginManager; got type "%s" instead',
                (is_object($helpers) ? get_class($helpers) : gettype($helpers))
            ));
        }
        $this->__helpers = $helpers;

        return $this;
    }

}
