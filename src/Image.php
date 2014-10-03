<?php

namespace Midnight\Image;

use Midnight\Image\Exception\FileNotFoundException;
use Midnight\Image\ImageFeature\FileNameAware;
use Midnight\Image\ImageFeature\HeightAware;
use Midnight\Image\ImageFeature\WidthAware;
use Midnight\Image\Plugin\PluginManager;
use RuntimeException;

/**
 * Class Image
 *
 * @package Midnight\Image
 * @method ImageInterface contain($width, $height)
 * @method ImageInterface cover($width, $height)
 */
class Image implements ImageInterface, WidthAware, HeightAware, FileNameAware
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var PluginManager
     */
    private $pluginManager;

    /**
     * @param string $file
     */
    private function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $file
     *
     * @return Image
     */
    public static function open($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException(sprintf('The file "%s" does not exist.', $file));
        }
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
     * @return int
     */
    public function getWidth()
    {
        return getimagesize($this->getFile())[0];
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return getimagesize($this->getFile())[1];
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return basename($this->file);
    }

    public function __call($name, $arguments)
    {
        $plugin = $this->getPluginManager()->get($name);
        // @codeCoverageIgnoreStart
        if (!is_callable($plugin)) {
            throw new RuntimeException('The plugin must be callable.');
        }
        // @codeCoverageIgnoreEnd
        array_unshift($arguments, $this);
        return call_user_func_array($plugin, $arguments);
    }

    /**
     * @return PluginManager
     */
    private function getPluginManager()
    {
        if (!$this->pluginManager) {
            $this->pluginManager = new PluginManager();
        }
        return $this->pluginManager;
    }
}
